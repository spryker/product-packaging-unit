<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

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

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $locale
     *
     * @return bool
     */
    public function hasTranslation($keyName, ?LocaleTransfer $locale = null)
    {
        return $this->glossaryFacade->hasTranslation($keyName, $locale);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function getTranslation($keyName, LocaleTransfer $locale)
    {
        return $this->glossaryFacade->getTranslation($keyName, $locale);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true)
    {
        return $this->glossaryFacade->createTranslation($keyName, $locale, $value);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateTranslation($keyName, $locale, $value, $isActive = true)
    {
        return $this->glossaryFacade->updateTranslation($keyName, $locale, $value);
    }

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName)
    {
        return $this->glossaryFacade->hasKey($keyName);
    }

    /**
     * @param string $keyName
     *
     * @return int
     */
    public function createKey($keyName)
    {
        return $this->glossaryFacade->createKey($keyName);
    }

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function deleteKey($keyName)
    {
        return $this->glossaryFacade->deleteKey($keyName);
    }

    /**
     * @param string $keyName
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return string
     */
    public function translate($keyName, array $data = [], ?LocaleTransfer $localeTransfer = null)
    {
        return $this->glossaryFacade->translate($keyName, $data, $localeTransfer);
    }
}
