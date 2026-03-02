<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductPackagingUnitTypeBuilder;
use Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitBusinessFactory;
use Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacade;
use Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group ProductPackagingUnitFacade
 * @group InstallProductPackagingUnitTypesTest
 * Add your own group annotations below this line
 */
class InstallProductPackagingUnitTypesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    public function testInstallProductPackagingUnitTypesShouldPersistInfrastructuralPackagingUnitTypes(): void
    {
        // Arrange
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())->build();
        $config = $this->createProductPackagingUnitConfigMock();
        $config->method('getInfrastructuralPackagingUnitTypes')
            ->willReturn([$productPackagingUnitTypeTransfer]);
        $factory = $this->createProductPackagingUnitBusinessFactory($config);
        $facade = $this->createProductPackagingUnitFacade($factory);

        // Act
        $facade->installProductPackagingUnitTypes();

        // Assert
        $productPackagingUnitTypeTransfer = $this->tester->getFacade()->findProductPackagingUnitTypeByName($productPackagingUnitTypeTransfer);
        $this->assertNotNull($productPackagingUnitTypeTransfer->getIdProductPackagingUnitType());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig
     */
    protected function createProductPackagingUnitConfigMock(): ProductPackagingUnitConfig
    {
        return $this->getMockBuilder(ProductPackagingUnitConfig::class)
            ->getMock();
    }

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig|\PHPUnit\Framework\MockObject\MockObject|null $config
     *
     * @return \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitBusinessFactory
     */
    protected function createProductPackagingUnitBusinessFactory(?ProductPackagingUnitConfig $config = null): ProductPackagingUnitBusinessFactory
    {
        /** @var \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitBusinessFactory $factory */
        $factory = $this->tester->getFactory();

        if ($config !== null) {
            $factory->setConfig($config);
        }

        return $factory;
    }

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitBusinessFactory|\PHPUnit\Framework\MockObject\MockObject|null $factory
     *
     * @return \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacade
     */
    protected function createProductPackagingUnitFacade(?ProductPackagingUnitBusinessFactory $factory = null): ProductPackagingUnitFacade
    {
        /** @var \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacade $facade */
        $facade = $this->tester->getFacade();

        if ($factory !== null) {
            $facade->setFactory($factory);
        }

        return $facade;
    }
}
