<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

interface ProductPackagingUnitToAvailabilityFacadeInterface
{
    public function isProductSellableForStore(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): bool;

    public function findOrCreateProductConcreteAvailabilityBySkuForStore(string $sku, StoreTransfer $storeTransfer): ?ProductConcreteAvailabilityTransfer;

    public function updateAvailabilityForStore(string $sku, StoreTransfer $storeTransfer): void;
}
