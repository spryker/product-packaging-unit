<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductPackagingUnitTypeBuilder;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class ProductPackagingUnitTypeHelper extends Module
{
    use DataCleanupHelperTrait;

    public function haveProductPackagingUnitType(array $override = []): ProductPackagingUnitTypeTransfer
    {
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())
            ->seed($override)
            ->build();

        $productPackagingUnitTypeTransfer = $this->storeProductPackagingUnitType($productPackagingUnitTypeTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productPackagingUnitTypeTransfer): void {
            $this->cleanupProductPackagingUnitType($productPackagingUnitTypeTransfer);
        });

        return $productPackagingUnitTypeTransfer;
    }

    protected function storeProductPackagingUnitType(ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer): ProductPackagingUnitTypeTransfer
    {
        $spyProductPackagingUnitTypeEntity = $this->getProductPackagingUnitTypeQuery()
            ->filterByName($productPackagingUnitTypeTransfer->getName())
            ->findOneOrCreate();

        $spyProductPackagingUnitTypeEntity->save();

        $this->debug(sprintf('Inserted product packaging unit type with name: %s', $productPackagingUnitTypeTransfer->getName()));

        $productPackagingUnitTypeTransfer->fromArray($spyProductPackagingUnitTypeEntity->toArray(), true);

        return $productPackagingUnitTypeTransfer;
    }

    protected function cleanupProductPackagingUnitType(ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer): void
    {
        $this->debug(sprintf('Deleting product packaging unit type with name: %s', $productPackagingUnitTypeTransfer->getName()));

        $this->getProductPackagingUnitTypeQuery()
            ->findByName($productPackagingUnitTypeTransfer->getIdProductPackagingUnitType())
            ->delete();
    }

    protected function getProductPackagingUnitTypeQuery(): SpyProductPackagingUnitTypeQuery
    {
        return SpyProductPackagingUnitTypeQuery::create();
    }
}
