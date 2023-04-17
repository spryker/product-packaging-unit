<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OmsStateCollectionTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ProductPackagingUnitFacadeInterface
{
    /**
     * Specification:
     * - Adds infrastructural packaging unit types to Persistence.
     *
     * @api
     *
     * @return void
     */
    public function installProductPackagingUnitTypes(): void;

    /**
     * Specification:
     * - Retrieves the list of infrastructural packaging unit type names.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return array<string>
     */
    public function getInfrastructuralProductPackagingUnitTypeNames(): array;

    /**
     * Specification:
     * - Returns the default packaging unit type name.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    public function getDefaultProductPackagingUnitTypeName(): string;

    /**
     * Specification:
     * - Retrieves a product packaging unit type using the name within the provided transfer.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @throws \Spryker\Zed\ProductPackagingUnit\Business\Exception\ProductPackagingUnitTypeNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer|null
     */
    public function findProductPackagingUnitTypeByName(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ?ProductPackagingUnitTypeTransfer;

    /**
     * Specification:
     * - Retrieves a product packaging unit type using the product packaging type ID within the provided transfer.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @throws \Spryker\Zed\ProductPackagingUnit\Business\Exception\ProductPackagingUnitTypeNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function getProductPackagingUnitTypeById(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer;

    /**
     * Specification:
     * - Retrieves the count of existing packaging units for a given product packaging unit type.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return int
     */
    public function countProductPackagingUnitsByTypeId(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): int;

    /**
     * Specification:
     * - Retrieves the list of product concrete IDs which are associated with any of the provided packaging unit type IDs.
     *
     * @api
     *
     * @param array<int> $productPackagingUnitTypeIds
     *
     * @return array<int>
     */
    public function findProductIdsByProductPackagingUnitTypeIds(array $productPackagingUnitTypeIds): array;

    /**
     * Specification:
     * - Creates product packaging unit type.
     * - Validates if the packaging unit type name is unique.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @throws \Spryker\Zed\ProductPackagingUnit\Business\Exception\ProductPackagingUnitTypeUniqueViolationException
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function createProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer;

    /**
     * Specification:
     * - Updates product packaging unit type.
     * - Validate if the name is still unique.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @throws \Spryker\Zed\ProductPackagingUnit\Business\Exception\ProductPackagingUnitTypeUniqueViolationException
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function updateProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer;

    /**
     * Specification:
     * - Deletes a product packaging unit type by its ID.
     * - Deletes related translations.
     * - Deletes only if no product packaging unit is associated with this unit type.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return bool
     */
    public function deleteProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): bool;

    /**
     * Specification:
     * - Expands CartChangeTransfer with amountSalesUnit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithAmountSalesUnit(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

    /**
     * Specification:
     * - Expands CartChangeTransfer with amountLeadProduct and productPackagingUnit of amount is specified.
     * - AmountSalesUnit is NOT required
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithProductPackagingUnit(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

    /**
     * Specification:
     * - Calculates amount normalized sales unit value.
     * - Updates quote item transfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function calculateAmountSalesUnitValueInQuote(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Expands group key if item has amount sales unit.
     * - Leaves the provided group key unchanged otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeGroupKeyWithAmount(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

    /**
     * Specification:
     * - Validates product amounts if they fulfill all amount restriction rules during item addition.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItemAddAmountRestrictions(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;

    /**
     * Specification:
     * - Expands SalesOrderItemEntity with amountSalesUnit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandSalesOrderItemWithAmountSalesUnit(
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity
    ): SpySalesOrderItemEntityTransfer;

    /**
     * Specification:
     * - Expands SalesOrderItemEntity with amount and sku if item has lead product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandSalesOrderItemWithAmountAndAmountSku(
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity
    ): SpySalesOrderItemEntityTransfer;

    /**
     * Specification:
     * - Checks if item amounts which being added to cart are available.
     * - Checks the quantity availability of the product packaging unit too, unless if it's a self-lead packaging unit.
     * - If a lead product is added separately, the lead product total availability amount will be checked.
     * - Sets error message if not available.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartChangeAmountAvailability(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;

    /**
     * Specification:
     * - Checks if item amounts which being added to cart are available during checkout.
     * - Even if same lead product added separately, the lead product availability is checked together.
     * - Sets error message if not available.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCheckoutAmountAvailability(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool;

    /**
     * Specification:
     * - Updates the lead product availability of the provided product packaging unit sku.
     * - Skips updating if the product packaging unit has self as lead product.
     *
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function updateLeadProductAvailability(string $sku): void;

    /**
     * Specification:
     * - Updates the lead product reservations of the provided product pacakaging unit sku.
     * - Skips updating if the product packaging unit has self as lead product.
     *
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function updateLeadProductReservation(string $sku): void;

    /**
     * Specification:
     * - Aggregates reservations for provided SKU both with or without packaging unit.
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\OmsStateCollectionTransfer $reservedStates
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer>
     */
    public function aggregateProductPackagingUnitReservation(
        string $sku,
        OmsStateCollectionTransfer $reservedStates,
        ?StoreTransfer $storeTransfer = null
    ): array;

    /**
     * Specification:
     * - Updates the product price for each item in cart according the corresponding specified amount.
     * - When amount is not specified, price is not changed.
     * - If amount is specified and differs to the default_amount it is calculated linearly based on the ratio of the
     * customer provided amount and default amount.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function setCustomAmountPrice(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

    /**
     * Specification:
     * - Hydrates order transfer with additional packaging unit sales unit.
     *
     * @api
     *
     * @deprecated Use {@link ProductPackagingUnitFacade::expandOrderItemsWithAmountSalesUnit()} instead.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithAmountSalesUnit(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Hydrates order items with additional packaging unit sales unit.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithAmountSalesUnit(array $itemTransfers): array;

    /**
     * Specification:
     * - Hydrates order transfer with additional packaging unit amount lead product.
     *
     * @api
     *
     * @deprecated Use {@link ProductPackagingUnitFacade::expandOrderItemsWithAmountLeadProduct()} instead.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithAmountLeadProduct(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Expands order items with additional packaging unit amount lead product.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithAmountLeadProduct(array $itemTransfers): array;

    /**
     * Specification:
     * - Splitting order item if product packaging unit item is splittable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function transformSplittableItem(ItemTransfer $itemTransfer): ItemCollectionTransfer;

    /**
     * Specification:
     * - Checks if the product packaging unit item is splittable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isProductPackagingUnitItemQuantitySplittable(ItemTransfer $itemTransfer): bool;

    /**
     * Specification:
     * - Merges $itemTransfer into $quoteTransfer.
     * - Appends it if it wasn't there.
     * - Increases quantity and amount if it was in quote items already.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItemToQuote(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Merges $itemTransfer into $quoteTransfer.
     * - Decreases quantity and amount if it was in quote items already.
     * - Removes the item from $quoteTransfer if quantity or amount equaled to zero after merging.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItemFromQuote(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Checks if packaging units are found for items in `CartChangeTransfer`.
     * - Checks items with amount set.
     * - Returns `CartPreCheckResponseTransfer` with an error in case packaging unit not found for item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartItemProductPackagingUnit(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;

    /**
     * Specification:
     * - Filters out products which have packaging unit available and returns back modified array.
     * - Requires ProductConcreteTransfer::idProductConcrete to be set.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function filterProductsWithoutPackagingUnit(array $productConcreteTransfers): array;

    /**
     * Specification:
     * - Requires `PickingListCollectionTransfer.pickingList.pickingListItem.orderItem` transfer property to be set.
     * - Expands `PickingListCollectionTransfer.pickingList.pickingListItem.orderItem` transfer objects with `amountSalesUnit` property.
     * - Returns expanded `PickingListCollectionTransfer` transfer object with amount sales unit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function expandPickingListCollection(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): PickingListCollectionTransfer;
}
