<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence;

use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductPackagingUnit\Dependency\External\ProductPackagingUnitToConnectionInterface;
use Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper\ProductMeasurementSalesUnitMapper;
use Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper\ProductPackagingUnitMapper;
use Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper\ProductPackagingUnitMapperInterface;
use Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig getConfig()
 * @method \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface getRepository()
 */
class ProductPackagingUnitPersistenceFactory extends AbstractPersistenceFactory
{
    public function createProductPackagingUnitTypeQuery(): SpyProductPackagingUnitTypeQuery
    {
        return SpyProductPackagingUnitTypeQuery::create();
    }

    public function createProductPackagingUnitQuery(): SpyProductPackagingUnitQuery
    {
        return SpyProductPackagingUnitQuery::create();
    }

    public function createProductPackagingUnitMapper(): ProductPackagingUnitMapperInterface
    {
        return new ProductPackagingUnitMapper();
    }

    public function createProductMeasurementSalesUnitMapper(): ProductMeasurementSalesUnitMapper
    {
        return new ProductMeasurementSalesUnitMapper();
    }

    public function getSalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::PROPEL_QUERY_SALES_ORDER_ITEM);
    }

    public function getConnection(): ProductPackagingUnitToConnectionInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::CONNECTION);
    }
}
