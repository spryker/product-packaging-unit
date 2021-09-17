<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Reader;

use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class ProductPackagingUnitReader implements ProductPackagingUnitReaderInterface
{
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
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function filterProductsWithoutPackagingUnit(array $productConcreteTransfers): array
    {
        if (!$productConcreteTransfers) {
            return [];
        }

        $productConcreteIds = $this->extractProductConcreteIdsFromProductConcreteTransfers($productConcreteTransfers);
        $productPackagingUnitCounts = $this->productPackagingUnitRepository->getProductPackagingUnitCountByProductConcreteIds($productConcreteIds);

        return $this->getEligibleProductConcreteTransfers($productConcreteTransfers, $productPackagingUnitCounts);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     * @param array<int> $productPackagingUnitCounts
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function getEligibleProductConcreteTransfers(array $productConcreteTransfers, array $productPackagingUnitCounts): array
    {
        $eligibleProductConcreteTransfers = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productConcretePackagingUnitCount = $productPackagingUnitCounts[$productConcreteTransfer->getIdProductConcrete()] ?? null;

            if (!$productConcretePackagingUnitCount) {
                $eligibleProductConcreteTransfers[] = $productConcreteTransfer;
            }
        }

        return $eligibleProductConcreteTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<int>
     */
    protected function extractProductConcreteIdsFromProductConcreteTransfers(array $productConcreteTransfers): array
    {
        $productConcreteIds = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productConcreteIds[] = $productConcreteTransfer->requireIdProductConcrete()->getIdProductConcrete();
        }

        return $productConcreteIds;
    }
}
