<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business\Plugin\Oms;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\WhereClauseTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Oms\ProductPackagingUnitReservationAggregationQueryCriteriaExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group Plugin
 * @group Oms
 * @group ProductPackagingUnitReservationAggregationQueryCriteriaExpanderPluginTest
 *
 * Add your own group annotations below this line
 */
class ProductPackagingUnitReservationAggregationQueryCriteriaExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SKU = 'concrete-sku-001';

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected $tester;

    public function testExpandAddsAmountSkuGroupByForPackagingUnitAwareness(): void
    {
        // Arrange
        $queryCriteriaTransfer = new QueryCriteriaTransfer();
        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setSku(static::SKU);

        // Act
        $resultQueryCriteriaTransfer = $this->createPlugin()->expand($queryCriteriaTransfer, $reservationRequestTransfer);

        // Assert
        $this->assertContains(
            SpySalesOrderItemTableMap::COL_AMOUNT_SKU,
            $resultQueryCriteriaTransfer->getGroupByColumns(),
            'Expected `spy_sales_order_item.amount_sku` to be grouped so per-amount totals are produced.',
        );
    }

    public function testExpandAddsOrWhereClauseMatchingSkuOrAmountSku(): void
    {
        // Arrange
        $queryCriteriaTransfer = new QueryCriteriaTransfer();
        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setSku(static::SKU);

        // Act
        $resultQueryCriteriaTransfer = $this->createPlugin()->expand($queryCriteriaTransfer, $reservationRequestTransfer);

        // Assert
        $whereClauseTransfers = $resultQueryCriteriaTransfer->getWhereClauses()->getArrayCopy();
        $this->assertCount(1, $whereClauseTransfers);

        $whereClauseTransfer = $whereClauseTransfers[0];
        $this->assertInstanceOf(WhereClauseTransfer::class, $whereClauseTransfer);
        $this->assertSame(
            sprintf(
                '%s = ? OR %s = ?',
                SpySalesOrderItemTableMap::COL_SKU,
                SpySalesOrderItemTableMap::COL_AMOUNT_SKU,
            ),
            $whereClauseTransfer->getClause(),
        );
        $this->assertSame([static::SKU, static::SKU], $whereClauseTransfer->getParameters());
    }

    public function testExpandRegistersConditionalSumAmountWithColumn(): void
    {
        // Arrange
        $queryCriteriaTransfer = new QueryCriteriaTransfer();
        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setSku(static::SKU);

        // Act
        $resultQueryCriteriaTransfer = $this->createPlugin()->expand($queryCriteriaTransfer, $reservationRequestTransfer);

        // Assert
        $withColumns = $resultQueryCriteriaTransfer->getWithColumns();
        $this->assertArrayHasKey(SalesOrderItemStateAggregationTransfer::SUM_AMOUNT, $withColumns);

        $sumAmountExpression = $withColumns[SalesOrderItemStateAggregationTransfer::SUM_AMOUNT];
        $this->assertStringContainsString('CASE WHEN', $sumAmountExpression);
        $this->assertStringContainsString(SpySalesOrderItemTableMap::COL_AMOUNT_SKU, $sumAmountExpression);
        $this->assertStringContainsString(SpySalesOrderItemTableMap::COL_AMOUNT, $sumAmountExpression);
        $this->assertStringContainsString(SpySalesOrderItemTableMap::COL_QUANTITY, $sumAmountExpression);
    }

    public function testExpandPreservesPreExistingCriteriaContributions(): void
    {
        // Arrange
        $preExistingWhereClauseTransfer = (new WhereClauseTransfer())
            ->setClause('spy_sales_order_item.product_offer_reference = ?')
            ->setParameters(['offer-ref-1']);

        $queryCriteriaTransfer = (new QueryCriteriaTransfer())
            ->addWhereClause($preExistingWhereClauseTransfer)
            ->addGroupByColumn(SpySalesOrderItemTableMap::COL_PRODUCT_OFFER_REFERENCE)
            ->addJoin(new QueryJoinTransfer());

        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setSku(static::SKU);

        // Act
        $resultQueryCriteriaTransfer = $this->createPlugin()->expand($queryCriteriaTransfer, $reservationRequestTransfer);

        // Assert
        $this->assertCount(2, $resultQueryCriteriaTransfer->getWhereClauses());
        $this->assertSame(
            [
                SpySalesOrderItemTableMap::COL_PRODUCT_OFFER_REFERENCE,
                SpySalesOrderItemTableMap::COL_AMOUNT_SKU,
            ],
            $resultQueryCriteriaTransfer->getGroupByColumns(),
        );
        $this->assertCount(1, $resultQueryCriteriaTransfer->getJoins());
    }

    public function testExpandReturnsTheSameQueryCriteriaTransferInstance(): void
    {
        // Arrange
        $queryCriteriaTransfer = new QueryCriteriaTransfer();
        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setSku(static::SKU);

        // Act
        $resultQueryCriteriaTransfer = $this->createPlugin()->expand($queryCriteriaTransfer, $reservationRequestTransfer);

        // Assert
        $this->assertSame($queryCriteriaTransfer, $resultQueryCriteriaTransfer);
    }

    protected function createPlugin(): ProductPackagingUnitReservationAggregationQueryCriteriaExpanderPlugin
    {
        return new ProductPackagingUnitReservationAggregationQueryCriteriaExpanderPlugin();
    }
}
