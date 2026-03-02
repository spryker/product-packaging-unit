<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\PreCheck;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeInterface;

abstract class ProductPackagingUnitAvailabilityPreCheck
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    public function __construct(ProductPackagingUnitToAvailabilityFacadeInterface $availabilityFacade)
    {
        $this->availabilityFacade = $availabilityFacade;
    }

    protected function isPackagingUnitSellable(ItemTransfer $itemTransfer, StoreTransfer $storeTransfer): bool
    {
        if ($this->isSelfLeadPackagingUnitItem($itemTransfer)) {
            return true;
        }

        return $this->isProductSellableForStore(
            $itemTransfer->getSku(),
            new Decimal($itemTransfer->getQuantity()),
            $storeTransfer,
        );
    }

    protected function isSelfLeadPackagingUnitItem(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getAmountLeadProduct()->getSku() === $itemTransfer->getSku();
    }

    protected function findProductConcreteAvailability(ItemTransfer $itemTransfer, StoreTransfer $storeTransfer): Decimal
    {
        $productConcreteAvailabilityTransfer = $this->availabilityFacade
            ->findOrCreateProductConcreteAvailabilityBySkuForStore($itemTransfer->getSku(), $storeTransfer);

        if ($productConcreteAvailabilityTransfer !== null) {
            return $productConcreteAvailabilityTransfer->getAvailability();
        }

        return new Decimal(0);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $items
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isPackagingUnitLeadProductSellable(ItemTransfer $itemTransfer, iterable $items, StoreTransfer $storeTransfer): bool
    {
        $itemLeadProductSku = $itemTransfer->getAmountLeadProduct()->getSku();
        $accumulatedItemLeadProductQuantity = $this->getAccumulatedQuantityForLeadProduct($items, $itemLeadProductSku);

        return $this->isProductSellableForStore(
            $itemLeadProductSku,
            $accumulatedItemLeadProductQuantity,
            $storeTransfer,
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param string $leadProductSku
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function getAccumulatedQuantityForLeadProduct(iterable $itemTransfers, string $leadProductSku): Decimal
    {
        $quantity = new Decimal(0);
        foreach ($itemTransfers as $itemTransfer) {
            if ($this->isLeadProductItemTransfer($leadProductSku, $itemTransfer)) {
                $quantity = $quantity->add($itemTransfer->getQuantity());

                continue;
            }

            if (!$this->isProductPackagingUnitItemTransfer($itemTransfer)) {
                continue;
            }

            if ($this->isProductPackagingUnitItemTransferOfLeadProduct($leadProductSku, $itemTransfer)) {
                $quantity = $quantity->add($itemTransfer->getAmount());
            }
        }

        return $quantity;
    }

    /**
     * Lead product is in cart as an individual item, but not self-lead.
     *
     * @param string $leadProductSku
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isLeadProductItemTransfer(string $leadProductSku, ItemTransfer $itemTransfer): bool
    {
        $amountLeadProduct = $itemTransfer->getAmountLeadProduct();
        $isQuantityLeadProduct = $leadProductSku === $itemTransfer->getSku();
        $isAmountLeadProduct = $amountLeadProduct && $leadProductSku === $amountLeadProduct->getSku();

        return $isQuantityLeadProduct && !$isAmountLeadProduct;
    }

    protected function isProductPackagingUnitItemTransfer(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getAmountLeadProduct() !== null;
    }

    protected function isProductPackagingUnitItemTransferOfLeadProduct(string $leadProductSku, ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getAmountLeadProduct()->getSku() === $leadProductSku;
    }

    protected function isProductSellableForStore(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): bool
    {
        return $this->availabilityFacade->isProductSellableForStore($sku, $quantity, $storeTransfer);
    }
}
