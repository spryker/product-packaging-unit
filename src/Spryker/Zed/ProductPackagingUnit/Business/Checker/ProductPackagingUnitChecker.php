<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Checker;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToStoreFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class ProductPackagingUnitChecker implements ProductPackagingUnitCheckerInterface
{
    protected const GLOSSARY_KEY_CART_ITEM_PRODUCT_PACKAGING_UNIT_IS_NOT_FOUND = 'cart.item.packaging_unit.not_found';

    protected const MESSAGE_TYPE_ERROR = 'error';

    protected const SKU_TRANSLATION_PARAMETER = '%sku%';

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface
     */
    protected $productPackagingUnitRepository;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository,
        ProductPackagingUnitToStoreFacadeInterface $storeFacade
    ) {
        $this->productPackagingUnitRepository = $productPackagingUnitRepository;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkProductPackagingUnit(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);
        $productConcreteSkus = $this->getProductConcreteSkus($cartChangeTransfer);
        if (!$productConcreteSkus) {
            return $cartPreCheckResponseTransfer;
        }

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $sku = $itemTransfer->getSku();
            $productPackagingUnitTransfer = $this->productPackagingUnitRepository->findProductPackagingUnitByProductSku($sku);
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
     * @return string[]
     */
    protected function getProductConcreteSkus(CartChangeTransfer $cartChangeTransfer): array
    {
        $productConcreteSkus = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getProductPackagingUnit()) {
                $productConcreteSkus[] = $itemTransfer->getSku();
            }
        }

        return $productConcreteSkus;
    }

    /**
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(array $parameters): MessageTransfer
    {
        return (new MessageTransfer())
            ->setType(static::MESSAGE_TYPE_ERROR)
            ->setValue(static::GLOSSARY_KEY_CART_ITEM_PRODUCT_PACKAGING_UNIT_IS_NOT_FOUND)
            ->setParameters($parameters);
    }
}
