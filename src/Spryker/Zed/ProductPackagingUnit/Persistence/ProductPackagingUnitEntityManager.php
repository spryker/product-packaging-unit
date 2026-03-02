<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitPersistenceFactory getFactory()
 */
class ProductPackagingUnitEntityManager extends AbstractEntityManager implements ProductPackagingUnitEntityManagerInterface
{
    public function saveProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeEntity = $this->getFactory()
            ->createProductPackagingUnitTypeQuery()
            ->filterByIdProductPackagingUnitType($productPackagingUnitTypeTransfer->getIdProductPackagingUnitType())
            ->findOneOrCreate();

        $productPackagingUnitTypeEntity->fromArray($productPackagingUnitTypeTransfer->modifiedToArray());
        $productPackagingUnitTypeEntity->save();

        $productPackagingUnitTypeTransfer->fromArray($productPackagingUnitTypeEntity->toArray(), true);

        return $productPackagingUnitTypeTransfer;
    }

    public function saveProductPackagingUnitTypeByName(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): void {
        $productPackagingUnitTypeEntity = $this->getFactory()
            ->createProductPackagingUnitTypeQuery()
            ->filterByName($productPackagingUnitTypeTransfer->getName())
            ->findOneOrCreate();

        $productPackagingUnitTypeEntity->fromArray($productPackagingUnitTypeTransfer->modifiedToArray());
        $productPackagingUnitTypeEntity->save();
    }

    public function deleteProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): bool {
        $productPackagingUnitTypeTransfer->requireIdProductPackagingUnitType();

        return $this->getFactory()
                ->createProductPackagingUnitTypeQuery()
                ->filterByIdProductPackagingUnitType($productPackagingUnitTypeTransfer->getIdProductPackagingUnitType())
                ->delete() > 0;
    }
}
