<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Cart;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductPackagingUnitCartOperationInterface
{
    public function addItemToQuote(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer;

    public function removeItemFromQuote(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer;
}
