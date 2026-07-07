<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Oms;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationAggregationQueryCriteriaExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface getRepository()
 */
class ProductPackagingUnitReservationAggregationQueryCriteriaExpanderPlugin extends AbstractPlugin implements OmsReservationAggregationQueryCriteriaExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Makes the OMS reservation aggregation packaging-unit-aware: when a sales item is sold
     *   as a packaging unit (e.g. a 6-pack), its `amount` of the underlying product is added
     *   to the reservation total instead of its wrapping `quantity`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expand(
        QueryCriteriaTransfer $queryCriteriaTransfer,
        ReservationRequestTransfer $reservationRequestTransfer
    ): QueryCriteriaTransfer {
        return $this->getRepository()->expandOmsReservationAggregationQueryCriteria(
            $queryCriteriaTransfer,
            $reservationRequestTransfer,
        );
    }
}
