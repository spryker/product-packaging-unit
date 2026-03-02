<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Installer;

use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitEntityManagerInterface;
use Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig;

class ProductPackagingUnitTypeInstaller implements ProductPackagingUnitTypeInstallerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig
     */
    protected $config;

    public function __construct(
        ProductPackagingUnitEntityManagerInterface $entityManager,
        ProductPackagingUnitConfig $config
    ) {
        $this->entityManager = $entityManager;
        $this->config = $config;
    }

    public function install(): void
    {
        $this->getTransactionHandler()->handleTransaction(function () {
            $this->executeInstallTransaction();
        });
    }

    protected function executeInstallTransaction(): void
    {
        $productInfrastructuralPackagingUnitTypes = $this->config->getInfrastructuralPackagingUnitTypes();

        foreach ($productInfrastructuralPackagingUnitTypes as $productInfrastructuralPackagingUnitTypeTransfer) {
            $this->entityManager->saveProductPackagingUnitTypeByName($productInfrastructuralPackagingUnitTypeTransfer);
        }
    }
}
