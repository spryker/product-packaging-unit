<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business\Plugin\Oms;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Oms\ProductPackagingUnitOmsReservationAggregationPlugin;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group Plugin
 * @group Oms
 * @group ProductPackagingUnitOmsReservationAggregationPluginTest
 * Add your own group annotations below this line
 */
class ProductPackagingUnitOmsReservationAggregationPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    public function testAggregateReservationsReturnsAmountSumForPackagingUnitSku(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $quantity = 2;
        $amount = new Decimal(2.5);
        $itemsCount = 5;
        [$stateCollectionTransfer, $leadProductConcreteTransfer] = $this->tester
            ->haveProductPackagingUnitWithSalesOrderItems($quantity, $amount, $itemsCount);

        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setSku($leadProductConcreteTransfer->getSku())
            ->setAmountSku($leadProductConcreteTransfer->getSku())
            ->setReservedStates($stateCollectionTransfer)
            ->setStore($storeTransfer);

        // Act
        $salesAggregationTransfers = (new ProductPackagingUnitOmsReservationAggregationPlugin())
            ->aggregateReservations($reservationRequestTransfer);

        // Assert
        $this->assertNotEmpty($salesAggregationTransfers);
        $this->assertContainsOnlyInstancesOf(SalesOrderItemStateAggregationTransfer::class, $salesAggregationTransfers);
        $this->assertSame(
            $amount->multiply($itemsCount)->toString(),
            $this->sumSalesAggregationTransfers($salesAggregationTransfers)->toString(),
        );
    }

    public function testAggregateReservationsReturnsQuantitySumForPlainSkuWithoutPackagingUnit(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $quantity = 3;
        $itemsCount = 7;
        $sku = 'NOT_PU_PRODUCT';
        $stateCollectionTransfer = $this->tester->haveSalesOrderWithItems($itemsCount, $quantity, $sku);

        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setSku($sku)
            ->setAmountSku($sku)
            ->setReservedStates($stateCollectionTransfer)
            ->setStore($storeTransfer);

        // Act
        $salesAggregationTransfers = (new ProductPackagingUnitOmsReservationAggregationPlugin())
            ->aggregateReservations($reservationRequestTransfer);

        // Assert
        $this->assertCount(1, $salesAggregationTransfers);
        $this->assertContainsOnlyInstancesOf(SalesOrderItemStateAggregationTransfer::class, $salesAggregationTransfers);
        $this->assertSame(
            $quantity * $itemsCount,
            $this->sumSalesAggregationTransfers($salesAggregationTransfers)->toInt(),
        );
    }

    public function testAggregateReservationsCombinesPackagingUnitAmountWithPlainQuantityForSameLeadSku(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $quantity = 4;
        $itemsCount = 9;
        $quantityInPackagingUnit = 6;
        $amountForPackagingUnit = new Decimal(1.2);
        $itemsCountInPackagingUnit = 3;
        [$stateCollectionTransfer, $leadProductConcreteTransfer] = $this->tester
            ->haveProductPackagingUnitWithSalesOrderItems($quantityInPackagingUnit, $amountForPackagingUnit, $itemsCountInPackagingUnit);
        $this->tester->haveSalesOrderWithItems($itemsCount, $quantity, $leadProductConcreteTransfer->getSku());

        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setSku($leadProductConcreteTransfer->getSku())
            ->setAmountSku($leadProductConcreteTransfer->getSku())
            ->setReservedStates($stateCollectionTransfer)
            ->setStore($storeTransfer);

        // Act
        $salesAggregationTransfers = (new ProductPackagingUnitOmsReservationAggregationPlugin())
            ->aggregateReservations($reservationRequestTransfer);

        // Assert
        $this->assertCount(2, $salesAggregationTransfers);
        $this->assertContainsOnlyInstancesOf(SalesOrderItemStateAggregationTransfer::class, $salesAggregationTransfers);
        $this->assertSame(
            $amountForPackagingUnit->multiply($itemsCountInPackagingUnit)->add($quantity * $itemsCount)->toString(),
            $this->sumSalesAggregationTransfers($salesAggregationTransfers)->toString(),
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer> $salesAggregationTransfers
     */
    protected function sumSalesAggregationTransfers(array $salesAggregationTransfers): Decimal
    {
        return array_reduce($salesAggregationTransfers, function (Decimal $result, SalesOrderItemStateAggregationTransfer $salesAggregationTransfer) {
            return $result->add($salesAggregationTransfer->getSumAmount())->trim();
        }, new Decimal(0));
    }
}
