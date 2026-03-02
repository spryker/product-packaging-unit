<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface;

class AmountSalesUnitItemExpander implements AmountSalesUnitItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface
     */
    protected $productMeasurementUnitFacade;

    public function __construct(
        ProductPackagingUnitToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
    ) {
        $this->productMeasurementUnitFacade = $productMeasurementUnitFacade;
    }

    public function expandCartWithAmountSalesUnit(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getAmount()) {
                continue;
            }

            $itemTransfer->requireAmountLeadProduct();

            if ($itemTransfer->getAmountLeadProduct()->getSku() === $itemTransfer->getSku()) {
                $this->resetAmountAndQuantitySalesUnits($itemTransfer);
            }

            $this->expandItemWithAmountSalesUnit($itemTransfer);
        }

        return $cartChangeTransfer;
    }

    protected function resetAmountAndQuantitySalesUnits(ItemTransfer $itemTransfer): ItemTransfer
    {
        $itemTransfer->setQuantitySalesUnit(null);

        return $itemTransfer;
    }

    protected function expandItemWithAmountSalesUnit(ItemTransfer $itemTransfer): ItemTransfer
    {
        $itemTransfer->getAmountSalesUnit()->requireIdProductMeasurementSalesUnit();

        $idProductMeasurementSalesUnit = $itemTransfer->getAmountSalesUnit()->getIdProductMeasurementSalesUnit();

        $productMeasurementUnitTransfer = $this->productMeasurementUnitFacade
            ->getProductMeasurementSalesUnitTransfer($idProductMeasurementSalesUnit);

        $itemTransfer->setAmountSalesUnit($productMeasurementUnitTransfer);

        return $itemTransfer;
    }
}
