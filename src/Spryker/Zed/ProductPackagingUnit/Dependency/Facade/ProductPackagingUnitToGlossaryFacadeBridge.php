<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;

class ProductPackagingUnitToGlossaryFacadeBridge implements ProductPackagingUnitToGlossaryFacadeInterface
{
    /**
     * @var \Spryker\Zed\Glossary\Business\GlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\Glossary\Business\GlossaryFacadeInterface $glossaryFacade
     */
    public function __construct($glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    public function hasTranslation(string $keyName, ?LocaleTransfer $localeTransfer = null): bool
    {
        return $this->glossaryFacade->hasTranslation($keyName, $localeTransfer);
    }

    public function getTranslation(string $keyName, LocaleTransfer $localeTransfer): TranslationTransfer
    {
        return $this->glossaryFacade->getTranslation($keyName, $localeTransfer);
    }

    public function createTranslation(string $keyName, LocaleTransfer $localeTransfer, string $value, bool $isActive = true): TranslationTransfer
    {
        return $this->glossaryFacade->createTranslation($keyName, $localeTransfer, $value);
    }

    public function updateTranslation(string $keyName, LocaleTransfer $localeTransfer, string $value, bool $isActive = true): TranslationTransfer
    {
        return $this->glossaryFacade->updateTranslation($keyName, $localeTransfer, $value);
    }

    public function hasKey(string $keyName): bool
    {
        return $this->glossaryFacade->hasKey($keyName);
    }

    public function createKey(string $keyName): int
    {
        return $this->glossaryFacade->createKey($keyName);
    }

    public function deleteKey(string $keyName): bool
    {
        return $this->glossaryFacade->deleteKey($keyName);
    }

    /**
     * @param string $keyName
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return string
     */
    public function translate(string $keyName, array $data = [], ?LocaleTransfer $localeTransfer = null): string
    {
        return $this->glossaryFacade->translate($keyName, $data, $localeTransfer);
    }
}
