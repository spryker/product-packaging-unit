<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductPackagingUnitToProductFacadeBridge implements ProductPackagingUnitToProductFacadeInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacadeInterface $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    public function findProductConcreteById(int $idProduct): ?ProductConcreteTransfer
    {
        return $this->productFacade->findProductConcreteById($idProduct);
    }

    public function findProductConcreteIdBySku(string $sku): ?int
    {
        return $this->productFacade->findProductConcreteIdBySku($sku);
    }

    /**
     * @param array<string> $skus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function findProductConcretesBySkus(array $skus): array
    {
        return $this->productFacade->findProductConcretesBySkus($skus);
    }
}
