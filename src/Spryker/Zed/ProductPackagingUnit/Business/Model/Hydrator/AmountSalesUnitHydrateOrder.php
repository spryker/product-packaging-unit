<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Hydrator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

/**
 * @deprecated Will be removed without replacement.
 */
class AmountSalesUnitHydrateOrder implements AmountSalesUnitHydrateOrderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface
     */
    protected $productPackagingUnitRepository;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface
     */
    protected $productMeasurementUnitFacade;

    public function __construct(
        ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository,
        ProductPackagingUnitToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
    ) {
        $this->productPackagingUnitRepository = $productPackagingUnitRepository;
        $this->productMeasurementUnitFacade = $productMeasurementUnitFacade;
    }

    public function expandOrderWithAmountSalesUnit(OrderTransfer $orderTransfer): OrderTransfer
    {
        $salesOrderItemEntityTransfers = $this->productPackagingUnitRepository
            ->findSalesOrderItemsByIdSalesOrder($orderTransfer->getIdSalesOrder());

        foreach ($salesOrderItemEntityTransfers as $salesOrderItemEntityTransfer) {
            $itemTransfer = $this->findItemTransferAmountSalesUnitsBelongTo(
                $orderTransfer,
                $salesOrderItemEntityTransfer->getIdSalesOrderItem(),
            );

            if (!$itemTransfer || !$salesOrderItemEntityTransfer->getAmountMeasurementUnitName()) {
                continue;
            }

            $this->setAmountSalesUnit($itemTransfer, $salesOrderItemEntityTransfer);
        }

        return $orderTransfer;
    }

    protected function setAmountSalesUnit(ItemTransfer $itemTransfer, SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer): void
    {
        $itemAmountMeasurementUnitTransfer = $this->hydrateItemAmountSalesUnitTransfer($salesOrderItemEntityTransfer);

        $itemTransfer->setAmountSalesUnit($itemAmountMeasurementUnitTransfer);
    }

    protected function hydrateItemAmountSalesUnitTransfer(SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer): ProductMeasurementSalesUnitTransfer
    {
        $productMeasurementSalesUnitTransfer = new ProductMeasurementSalesUnitTransfer();
        $productMeasurementSalesUnitTransfer->setConversion($salesOrderItemEntityTransfer->getAmountMeasurementUnitConversion());
        $productMeasurementSalesUnitTransfer->setPrecision($salesOrderItemEntityTransfer->getAmountMeasurementUnitPrecision());

        $productMeasurementBaseUnitTransfer = $this->createProductMeasurementBaseUnitTransfer($salesOrderItemEntityTransfer);
        $productMeasurementSalesUnitTransfer->setProductMeasurementBaseUnit($productMeasurementBaseUnitTransfer);

        $productMeasurementUnitTransfer = $this->createProductMeasurementUnitTransfer(
            $salesOrderItemEntityTransfer->getAmountMeasurementUnitName(),
            $salesOrderItemEntityTransfer->getAmountMeasurementUnitCode(),
        );
        $productMeasurementSalesUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        $this->productMeasurementUnitFacade->translateProductMeasurementSalesUnit($productMeasurementSalesUnitTransfer);

        return $productMeasurementSalesUnitTransfer;
    }

    protected function createProductMeasurementUnitTransfer(
        ?string $productMeasurementUnitName,
        ?string $productMeasurementUnitCode = null
    ): ProductMeasurementUnitTransfer {
        $productMeasurementUnitTransfer = new ProductMeasurementUnitTransfer();
        $productMeasurementUnitTransfer->setName($productMeasurementUnitName ?: '');

        if ($productMeasurementUnitCode !== null) {
            $productMeasurementUnitTransfer->setCode($productMeasurementUnitCode);
        }

        return $productMeasurementUnitTransfer;
    }

    protected function createProductMeasurementBaseUnitTransfer(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
    ): ProductMeasurementBaseUnitTransfer {
        $productMeasurementBaseUnitTransfer = new ProductMeasurementBaseUnitTransfer();
        $amountBaseMeasurementUnitName = $salesOrderItemEntityTransfer->getAmountBaseMeasurementUnitName() ?? '';
        $productMeasurementUnitTransfer = $this->createProductMeasurementUnitTransfer($amountBaseMeasurementUnitName);
        $productMeasurementBaseUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        return $productMeasurementBaseUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItemTransferAmountSalesUnitsBelongTo(OrderTransfer $orderTransfer, $idSalesOrderItem): ?ItemTransfer
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getIdSalesOrderItem() === $idSalesOrderItem) {
                return $itemTransfer;
            }
        }

        return null;
    }
}
