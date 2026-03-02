<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class ProductPackagingUnitItemExpander implements ProductPackagingUnitItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface
     */
    protected $productPackagingUnitRepository;

    public function __construct(ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository)
    {
        $this->productPackagingUnitRepository = $productPackagingUnitRepository;
    }

    public function expandCartChangeWithProductPackagingUnit(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getAmount()) {
                continue;
            }

            $this->expandItem($itemTransfer);
        }

        return $cartChangeTransfer;
    }

    protected function expandItem(ItemTransfer $itemTransfer): ItemTransfer
    {
        $itemTransfer = $this->expandItemWithLeadProduct($itemTransfer);
        $itemTransfer = $this->expandItemWithProductPackagingUnit($itemTransfer);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function expandItemWithLeadProduct(ItemTransfer $itemTransfer)
    {
        $productPackagingLeadProductTransfer = $this->productPackagingUnitRepository
            ->findProductPackagingUnitLeadProductForPackagingUnit($itemTransfer->getSku());

        if ($productPackagingLeadProductTransfer) {
            $itemTransfer->setAmountLeadProduct($productPackagingLeadProductTransfer);
        }

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function expandItemWithProductPackagingUnit(ItemTransfer $itemTransfer)
    {
        $productPackagingUnitTransfer = $this->productPackagingUnitRepository
            ->findProductPackagingUnitByProductId($itemTransfer->getId());

        if ($productPackagingUnitTransfer) {
            $itemTransfer->setProductPackagingUnit($productPackagingUnitTransfer);
        }

        return $itemTransfer;
    }
}
