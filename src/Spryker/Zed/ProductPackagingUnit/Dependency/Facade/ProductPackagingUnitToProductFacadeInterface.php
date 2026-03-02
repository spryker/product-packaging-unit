<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductPackagingUnitToProductFacadeInterface
{
    public function findProductConcreteById(int $idProduct): ?ProductConcreteTransfer;

    public function findProductConcreteIdBySku(string $sku): ?int;

    /**
     * @param array<string> $skus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function findProductConcretesBySkus(array $skus): array;
}
