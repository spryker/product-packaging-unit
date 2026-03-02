<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Checker;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class CartItemProductPackagingUnitChecker implements CartItemProductPackagingUnitCheckerInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CART_ITEM_PRODUCT_PACKAGING_UNIT_IS_NOT_FOUND = 'cart.item.packaging_unit.not_found';

    /**
     * @var string
     */
    protected const MESSAGE_TYPE_ERROR = 'error';

    /**
     * @var string
     */
    protected const SKU_TRANSLATION_PARAMETER = '%sku%';

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface
     */
    protected $productPackagingUnitRepository;

    public function __construct(ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository)
    {
        $this->productPackagingUnitRepository = $productPackagingUnitRepository;
    }

    public function checkCartItemProductPackagingUnit(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);
        $productConcreteSkus = $this->getProductConcreteSkus($cartChangeTransfer);
        $productPackagingUnitTransfers = $this->productPackagingUnitRepository->findProductPackagingUnitsByProductSku($productConcreteSkus);
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getAmount()) {
                continue;
            }

            $sku = $itemTransfer->getSku();
            $productPackagingUnitTransfer = $productPackagingUnitTransfers[$sku] ?? null;
            if ($productPackagingUnitTransfer) {
                continue;
            }

            $cartPreCheckResponseTransfer
                ->addMessage($this->createMessageTransfer([static::SKU_TRANSLATION_PARAMETER => $sku]))
                ->setIsSuccess(false);
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<string>
     */
    protected function getProductConcreteSkus(CartChangeTransfer $cartChangeTransfer): array
    {
        $productConcreteSkus = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $productConcreteSkus[] = $itemTransfer->getSku();
        }

        return $productConcreteSkus;
    }

    protected function createMessageTransfer(array $parameters): MessageTransfer
    {
        return (new MessageTransfer())
            ->setType(static::MESSAGE_TYPE_ERROR)
            ->setValue(static::GLOSSARY_KEY_CART_ITEM_PRODUCT_PACKAGING_UNIT_IS_NOT_FOUND)
            ->setParameters($parameters);
    }
}
