<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;

interface ProductPackagingUnitTypeKeyGeneratorInterface
{
    public function generateProductPackagingUnitTypeKey(ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer): void;
}
