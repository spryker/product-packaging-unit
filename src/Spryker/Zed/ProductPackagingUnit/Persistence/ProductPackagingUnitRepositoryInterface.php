<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ProductPackagingUnitRepositoryInterface
{
    public function findProductPackagingUnitTypeByName(
        string $productPackagingUnitTypeName
    ): ?ProductPackagingUnitTypeTransfer;

    public function findProductPackagingUnitTypeById(
        int $idProductPackagingUnitType
    ): ?ProductPackagingUnitTypeTransfer;

    public function countProductPackagingUnitsByTypeId(
        int $idProductPackagingUnitType
    ): int;

    public function findProductPackagingUnitLeadProductForPackagingUnit(
        string $siblingProductSku
    ): ?ProductConcreteTransfer;

    /**
     * @param array<int> $productPackagingUnitTypeIds
     *
     * @return array<int>
     */
    public function findProductIdsByProductPackagingUnitTypeIds(array $productPackagingUnitTypeIds): array;

    public function findProductPackagingUnitById(
        int $idProductPackagingUnit
    ): ?ProductPackagingUnitTransfer;

    public function findProductPackagingUnitByProductId(
        int $idProduct
    ): ?ProductPackagingUnitTransfer;

    public function findProductPackagingUnitByProductSku(
        string $productSku
    ): ?ProductPackagingUnitTransfer;

    /**
     * @param array<string> $productSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductPackagingUnitTransfer>
     */
    public function findProductPackagingUnitsByProductSku(
        array $productSkus
    ): array;

    /**
     * @param int $idSalesOrder
     *
     * @return array<\Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer>
     */
    public function findSalesOrderItemsByIdSalesOrder(int $idSalesOrder): array;

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return array<string>
     */
    public function getMappedLeadProductSkusBySalesOrderItemIds(array $salesOrderItemIds): array;

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>
     */
    public function getMappedProductMeasurementSalesUnits(array $salesOrderItemIds): array;

    /**
     * @uses State
     *
     * @param string $sku
     * @param array<string> $reservedStateNames
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer>
     */
    public function aggregateProductPackagingUnitReservation(string $sku, array $reservedStateNames, ?StoreTransfer $storeTransfer = null): array;

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<int>
     */
    public function getProductPackagingUnitCountByProductConcreteIds(array $productConcreteIds): array;
}
