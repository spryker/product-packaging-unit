<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\External;

use Propel\Runtime\Connection\ConnectionInterface;

class ProductPackagingUnitToConnectionAdapter implements ProductPackagingUnitToConnectionInterface
{
    public function __construct(protected ConnectionInterface $propelConnection)
    {
    }

    public function quote(string $value): string|false
    {
        return $this->propelConnection->quote($value);
    }
}
