<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

class ProductPackagingUnitToAvailabilityFacadeBridge implements ProductPackagingUnitToAvailabilityFacadeInterface
{
    /**
     * @var \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @param \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface $availabilityFacade
     */
    public function __construct($availabilityFacade)
    {
        $this->availabilityFacade = $availabilityFacade;
    }

    public function isProductSellableForStore(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): bool
    {
        return $this->availabilityFacade->isProductSellableForStore($sku, $quantity, $storeTransfer);
    }

    public function findOrCreateProductConcreteAvailabilityBySkuForStore(string $sku, StoreTransfer $storeTransfer): ?ProductConcreteAvailabilityTransfer
    {
        return $this->availabilityFacade->findOrCreateProductConcreteAvailabilityBySkuForStore($sku, $storeTransfer);
    }

    public function updateAvailabilityForStore(string $sku, StoreTransfer $storeTransfer): void
    {
        $this->availabilityFacade->updateAvailabilityForStore($sku, $storeTransfer);
    }
}
