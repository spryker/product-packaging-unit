<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Oms;

use Generated\Shared\Transfer\OmsStateCollectionTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationAggregationStrategyPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Oms\ProductPackagingUnitOmsReservationAggregationPlugin}
 *
 * @method \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnit\Communication\ProductPackagingUnitCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig getConfig()
 */
class ProductPackagingUnitReservationAggregationStrategyPlugin extends AbstractPlugin implements ReservationAggregationStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Aggregates reservations for provided SKU both with or without packaging unit.
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\OmsStateCollectionTransfer $reservedStates
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer>
     */
    public function aggregateReservations(string $sku, OmsStateCollectionTransfer $reservedStates, ?StoreTransfer $storeTransfer = null): array
    {
        return $this->getFacade()->aggregateProductPackagingUnitReservation($sku, $reservedStates, $storeTransfer);
    }
}
