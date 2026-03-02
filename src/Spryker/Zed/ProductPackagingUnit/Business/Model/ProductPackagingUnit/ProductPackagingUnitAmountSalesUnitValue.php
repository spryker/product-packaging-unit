<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\DecimalObject\Decimal;

class ProductPackagingUnitAmountSalesUnitValue implements ProductPackagingUnitAmountSalesUnitValueInterface
{
    /**
     * @var int
     */
    protected const DIVISION_SCALE = 10;

    public function calculateAmountSalesUnitValueInQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getAmountSalesUnit()) {
                continue;
            }

            $itemTransfer->getAmountSalesUnit()->setValue(
                $this->calculateAmountSalesUnitValue($itemTransfer),
            );
        }

        return $quoteTransfer;
    }

    protected function calculateAmountSalesUnitValue(ItemTransfer $itemTransfer): int
    {
        $itemTransfer
            ->requireAmountSalesUnit()
            ->requireAmount()
            ->requireQuantity()
            ->getAmountSalesUnit()
                ->requireConversion()
                ->requirePrecision();

        $amountPreQuantity = $itemTransfer->getAmount()->divide($itemTransfer->getQuantity(), static::DIVISION_SCALE);

        return $this->calculateValue(
            $amountPreQuantity,
            $itemTransfer->getAmountSalesUnit()->getConversion(),
            $itemTransfer->getAmountSalesUnit()->getPrecision(),
        );
    }

    protected function calculateValue(Decimal $availabilityValue, float $unitToAvailabilityConversion, int $unitPrecision): int
    {
        return $availabilityValue
            ->divide($unitToAvailabilityConversion, static::DIVISION_SCALE)
            ->multiply($unitPrecision)
            ->round(0, Decimal::ROUND_HALF_UP)
            ->toInt();
    }
}
