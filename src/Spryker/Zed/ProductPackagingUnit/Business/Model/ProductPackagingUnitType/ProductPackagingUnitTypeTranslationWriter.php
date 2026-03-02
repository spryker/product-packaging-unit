<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToGlossaryFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToLocaleFacadeInterface;

class ProductPackagingUnitTypeTranslationWriter implements ProductPackagingUnitTypeTranslationWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    public function __construct(
        ProductPackagingUnitToLocaleFacadeInterface $localeFacade,
        ProductPackagingUnitToGlossaryFacadeInterface $glossaryFacade
    ) {
        $this->localeFacade = $localeFacade;
        $this->glossaryFacade = $glossaryFacade;
    }

    public function saveTranslations(ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer): void
    {
        foreach ($productPackagingUnitTypeTransfer->getTranslations() as $productPackagingUnitTypeTranslationTransfer) {
            $productPackagingUnitTypeTranslationTransfer->requireLocaleCode();

            $this->saveNameTranslations($productPackagingUnitTypeTransfer, $productPackagingUnitTypeTranslationTransfer);
        }
    }

    protected function saveNameTranslations(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer,
        ProductPackagingUnitTypeTranslationTransfer $productPackagingUnitTypeTranslationTransfer
    ): void {
        $key = $productPackagingUnitTypeTransfer->getName();
        $value = $productPackagingUnitTypeTranslationTransfer->getName();

        if (!$key || !$value) {
            return;
        }

        $this->saveTranslation($key, $value, $productPackagingUnitTypeTranslationTransfer->getLocaleCode());
    }

    public function deleteTranslations(ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer): bool
    {
        $productPackagingUnitTypeTransfer->requireName();

        $translationKey = $productPackagingUnitTypeTransfer->getName();

        return $this->glossaryFacade->deleteKey($translationKey);
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $localeCode
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    protected function saveTranslation($key, $value, $localeCode): TranslationTransfer
    {
        if (!$this->glossaryFacade->hasKey($key)) {
            $this->glossaryFacade->createKey($key);
        }

        $localeTransfer = $this->localeFacade->getLocale($localeCode);

        if (!$this->glossaryFacade->hasTranslation($key, $localeTransfer)) {
            return $this->glossaryFacade->createTranslation($key, $localeTransfer, $value);
        } else {
            return $this->glossaryFacade->updateTranslation($key, $localeTransfer, $value);
        }
    }
}
