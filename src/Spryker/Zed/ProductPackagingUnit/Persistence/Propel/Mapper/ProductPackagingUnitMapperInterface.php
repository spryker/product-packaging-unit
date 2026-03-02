<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnit;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitType;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

interface ProductPackagingUnitMapperInterface
{
    public function mapProductPackagingUnitTransfer(
        SpyProductPackagingUnit $productPackagingUnitEntity,
        ProductPackagingUnitTransfer $productPackagingUnitTransfer
    ): ProductPackagingUnitTransfer;

    public function mapProductPackagingUnitTypeTransfer(
        SpyProductPackagingUnitType $spyProductPackagingUnitType,
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer;

    public function mapSpySalesOrderItemEntityTransfer(
        SpySalesOrderItem $salesOrderItemEntity,
        SpySalesOrderItemEntityTransfer $spySalesOrderItemEntityTransfer
    ): SpySalesOrderItemEntityTransfer;

    public function mapProductEntityToProductConcreteTransfer(
        SpyProduct $productEntity,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer;
}
