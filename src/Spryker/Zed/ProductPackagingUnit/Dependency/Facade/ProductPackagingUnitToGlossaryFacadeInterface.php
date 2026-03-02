<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;

interface ProductPackagingUnitToGlossaryFacadeInterface
{
    public function hasTranslation(string $keyName, ?LocaleTransfer $localeTransfer = null): bool;

    public function getTranslation(string $keyName, LocaleTransfer $localeTransfer): TranslationTransfer;

    public function createTranslation(string $keyName, LocaleTransfer $localeTransfer, string $value, bool $isActive = true): TranslationTransfer;

    public function updateTranslation(string $keyName, LocaleTransfer $localeTransfer, string $value, bool $isActive = true): TranslationTransfer;

    public function hasKey(string $keyName): bool;

    /**
     * @param string $keyName
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\KeyExistsException
     *
     * @return int
     */
    public function createKey(string $keyName): int;

    public function deleteKey(string $keyName): bool;

    /**
     * @param string $keyName
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return string
     */
    public function translate(string $keyName, array $data = [], ?LocaleTransfer $localeTransfer = null): string;
}
