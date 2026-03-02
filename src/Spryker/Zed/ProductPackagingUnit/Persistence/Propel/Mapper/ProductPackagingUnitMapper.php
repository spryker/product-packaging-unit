<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnit;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitType;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

class ProductPackagingUnitMapper implements ProductPackagingUnitMapperInterface
{
    public function mapProductPackagingUnitTransfer(
        SpyProductPackagingUnit $productPackagingUnitEntity,
        ProductPackagingUnitTransfer $productPackagingUnitTransfer
    ): ProductPackagingUnitTransfer {
        $productPackagingUnitTransfer->fromArray($productPackagingUnitEntity->toArray(), true);

        $productPackagingUnitTransfer->setProductPackagingUnitAmount(
            (new ProductPackagingUnitAmountTransfer())->fromArray($productPackagingUnitEntity->toArray(), true),
        );

        $productPackagingUnitTransfer->setProductPackagingUnitType(
            $this->mapProductPackagingUnitTypeTransfer(
                $productPackagingUnitEntity->getProductPackagingUnitType(),
                new ProductPackagingUnitTypeTransfer(),
            ),
        );

        return $productPackagingUnitTransfer;
    }

    public function mapProductPackagingUnitTypeTransfer(
        SpyProductPackagingUnitType $spyProductPackagingUnitType,
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeTransfer->fromArray($spyProductPackagingUnitType->toArray(), true);

        return $productPackagingUnitTypeTransfer;
    }

    public function mapSpySalesOrderItemEntityTransfer(
        SpySalesOrderItem $salesOrderItemEntity,
        SpySalesOrderItemEntityTransfer $spySalesOrderItemEntityTransfer
    ): SpySalesOrderItemEntityTransfer {
        $spySalesOrderItemEntityTransfer->fromArray($salesOrderItemEntity->toArray(), true);

        return $spySalesOrderItemEntityTransfer;
    }

    public function mapProductEntityToProductConcreteTransfer(
        SpyProduct $productEntity,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer {
        return $productConcreteTransfer->fromArray($productEntity->toArray(), true);
    }
}
