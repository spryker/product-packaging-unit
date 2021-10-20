<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Validator;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class ProductPackagingUnitAmountRestrictionValidator implements ProductPackagingUnitAmountRestrictionValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_AMOUNT_MIN_NOT_FULFILLED = 'cart.pre.check.amount.min.failed';

    /**
     * @var string
     */
    protected const ERROR_AMOUNT_MAX_NOT_FULFILLED = 'cart.pre.check.amount.max.failed';

    /**
     * @var string
     */
    protected const ERROR_AMOUNT_INTERVAL_NOT_FULFILLED = 'cart.pre.check.amount.interval.failed';

    /**
     * @var string
     */
    protected const ERROR_AMOUNT_IS_NOT_VARIABLE = 'cart.pre.check.amount.is_not_variable.failed';

    /**
     * @var int
     */
    protected const DIVISION_SCALE = 10;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface
     */
    protected $productPackagingUnitRepository;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository
     */
    public function __construct(ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository)
    {
        $this->productPackagingUnitRepository = $productPackagingUnitRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItemAddition(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);

        $cartPreCheckResponseTransfer = $this->validateItemsAmounts($cartChangeTransfer, $cartPreCheckResponseTransfer);

        return $cartPreCheckResponseTransfer->setIsSuccess(
            $cartPreCheckResponseTransfer->getMessages()->count() === 0,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function validateItemsAmounts(
        CartChangeTransfer $cartChangeTransfer,
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
    ): CartPreCheckResponseTransfer {
        $itemTransfers = $this->selectItemTransfersWithAmountSalesUnit($cartChangeTransfer);

        if (!$itemTransfers) {
            return $cartPreCheckResponseTransfer;
        }

        $changedSkuMapByGroupKey = $this->getChangedSkuMap($itemTransfers);
        $cartAmountMapByGroupKey = $this->getItemAddCartAmountMap($itemTransfers, $cartChangeTransfer);
        $productPackagingUnitAmountTransferMapBySku = $this->getProductPackagingUnitAmountTransferMap($itemTransfers);

        foreach ($cartAmountMapByGroupKey as $productGroupKey => $cartAmount) {
            $productSku = $changedSkuMapByGroupKey[$productGroupKey];
            if ($cartAmount->isZero()) {
                continue;
            }

            $cartPreCheckResponseTransfer = $this->validateItem($productSku, $cartAmount, $productPackagingUnitAmountTransferMapBySku[$productSku], $cartPreCheckResponseTransfer);
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function selectItemTransfersWithAmountSalesUnit(CartChangeTransfer $cartChangeTransfer): array
    {
        $packagingUnitItemTransfers = [];
        $itemTransfers = $cartChangeTransfer->getItems();

        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getAmountSalesUnit()) {
                continue;
            }
            $packagingUnitItemTransfers[] = $itemTransfer;
        }

        return $packagingUnitItemTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string>
     */
    protected function getChangedSkuMap(array $itemTransfers): array
    {
        $skuMap = [];

        foreach ($itemTransfers as $itemTransfer) {
            $skuMap[$itemTransfer->getGroupKey()] = $itemTransfer->getSku();
        }

        return $skuMap;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<\Spryker\DecimalObject\Decimal>
     */
    protected function getItemAddCartAmountMap(array $itemTransfers, CartChangeTransfer $cartChangeTransfer): array
    {
        $quoteAmountMapByGroupKey = $this->getQuoteAmountMap($cartChangeTransfer);
        /** @var array<\Spryker\DecimalObject\Decimal> $cartAmountMap */
        $cartAmountMap = [];

        foreach ($itemTransfers as $itemTransfer) {
            $productGroupKey = $itemTransfer->getGroupKey();
            $amountPerQuantity = $itemTransfer->getAmount()->divide($itemTransfer->getQuantity(), static::DIVISION_SCALE);
            $cartAmountMap[$productGroupKey] = $amountPerQuantity;

            if (isset($quoteAmountMapByGroupKey[$productGroupKey])) {
                $cartAmountMap[$productGroupKey] = $cartAmountMap[$productGroupKey]->add(
                    $quoteAmountMapByGroupKey[$productGroupKey],
                );
            }
        }

        return $cartAmountMap;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array
     */
    protected function getQuoteAmountMap(CartChangeTransfer $cartChangeTransfer): array
    {
        $quoteAmountMap = [];
        foreach ($cartChangeTransfer->getQuote()->getItems() as $itemTransfer) {
            if (!$itemTransfer->getAmount()) {
                continue;
            }
            $amountPerQuantity = $itemTransfer->getAmount()->divide($itemTransfer->getQuantity(), static::DIVISION_SCALE);
            $quoteAmountMap[$itemTransfer->getGroupKey()] = $amountPerQuantity;
        }

        return $quoteAmountMap;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer> .
     */
    protected function getProductPackagingUnitAmountTransferMap(array $itemTransfers): array
    {
        $productPackagingUnitAmountTransferMap = $this->mapProductPackagingUnitAmountTransfersBySku($itemTransfers);

        return $productPackagingUnitAmountTransferMap;
    }

    /**
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $amount
     * @param \Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer $productPackagingUnitAmountTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function validateItem(
        string $sku,
        Decimal $amount,
        ProductPackagingUnitAmountTransfer $productPackagingUnitAmountTransfer,
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
    ): CartPreCheckResponseTransfer {
        $productPackagingUnitAmountTransfer
            ->requireIsAmountVariable()
            ->requireDefaultAmount();

        $isAmountVariableCaseMessage = $this->validateItemIsAmountVariableCase($sku, $amount, $productPackagingUnitAmountTransfer);
        if ($isAmountVariableCaseMessage !== null) {
            return $cartPreCheckResponseTransfer
                ->addMessage($isAmountVariableCaseMessage);
        }

        $minCaseMessage = $this->validateItemMinCases($sku, $amount, $productPackagingUnitAmountTransfer);
        if ($minCaseMessage !== null) {
            $cartPreCheckResponseTransfer->addMessage($minCaseMessage);
        }

        $maxCaseMessage = $this->validateItemMaxCases($sku, $amount, $productPackagingUnitAmountTransfer);
        if ($maxCaseMessage !== null) {
            $cartPreCheckResponseTransfer->addMessage($maxCaseMessage);
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $amount
     * @param \Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer $productPackagingUnitAmountTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    protected function validateItemIsAmountVariableCase(
        string $sku,
        Decimal $amount,
        ProductPackagingUnitAmountTransfer $productPackagingUnitAmountTransfer
    ): ?MessageTransfer {
        $defaultAmount = $productPackagingUnitAmountTransfer->getDefaultAmount();

        if (!$productPackagingUnitAmountTransfer->getIsAmountVariable() && !$amount->mod($defaultAmount)->isZero()) {
            return $this->createMessageTransfer(static::ERROR_AMOUNT_IS_NOT_VARIABLE, $sku, $defaultAmount, $amount);
        }

        return null;
    }

    /**
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $amount
     * @param \Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer $productPackagingUnitAmountTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    protected function validateItemMinCases(
        string $sku,
        Decimal $amount,
        ProductPackagingUnitAmountTransfer $productPackagingUnitAmountTransfer
    ): ?MessageTransfer {
        $min = $productPackagingUnitAmountTransfer->getAmountMin();
        if ($min === null) {
            return null;
        }

        if ($amount->lessThan($min)) {
            return $this->createMessageTransfer(static::ERROR_AMOUNT_MIN_NOT_FULFILLED, $sku, $min, $amount);
        }

        $interval = $productPackagingUnitAmountTransfer->getAmountInterval();
        if ($interval !== null && !$amount->subtract($min)->mod($interval)->isZero()) {
            return $this->createMessageTransfer(static::ERROR_AMOUNT_INTERVAL_NOT_FULFILLED, $sku, $interval, $amount);
        }

        return null;
    }

    /**
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $amount
     * @param \Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer $productPackagingUnitAmountTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    protected function validateItemMaxCases(
        string $sku,
        Decimal $amount,
        ProductPackagingUnitAmountTransfer $productPackagingUnitAmountTransfer
    ): ?MessageTransfer {
        $max = $productPackagingUnitAmountTransfer->getAmountMax();

        if ($max !== null && $amount->greaterThan($max)) {
            return $this->createMessageTransfer(static::ERROR_AMOUNT_MAX_NOT_FULFILLED, $sku, $max, $amount);
        }

        return null;
    }

    /**
     * @param string $message
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $restrictionValue
     * @param \Spryker\DecimalObject\Decimal $actualValue
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(
        string $message,
        string $sku,
        Decimal $restrictionValue,
        Decimal $actualValue
    ): MessageTransfer {
        return (new MessageTransfer())
            ->setValue($message)
            ->setParameters([
                '%sku%' => $sku,
                '%restrictionValue%' => $restrictionValue->truncate(2)->toString(),
                '%actualValue%' => $actualValue->truncate(2)->toString(),
            ]);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer>
     */
    protected function mapProductPackagingUnitAmountTransfersBySku(array $itemTransfers): array
    {
        $productPackagingUnitAmountTransferMap = [];
        foreach ($itemTransfers as $itemTransfer) {
            $productPackagingUnitTransfer = $this->productPackagingUnitRepository
                ->findProductPackagingUnitByProductSku($itemTransfer->getSku());

            if ($productPackagingUnitTransfer) {
                $productPackagingUnitAmountTransferMap[$itemTransfer->getSku()] = $productPackagingUnitTransfer->getProductPackagingUnitAmount();
            }
        }

        return $productPackagingUnitAmountTransferMap;
    }
}
