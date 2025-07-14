<?php
declare(strict_types=1);

namespace App\Service;

use App\Cache\PromotionCache;
use App\DTO\LowestPriceEnquiry;
use App\DTO\PriceEnquiryInterface;
use App\Filter\PromotionsFilterInterface;
use App\Repository\ProductRepository;
use Psr\Cache\InvalidArgumentException;

class LowestPriceFinder
{
    public function __construct(
        private readonly ProductRepository         $repository,
        private readonly PromotionsFilterInterface $promotionsFilter,
        private readonly PromotionCache            $promotionCache
    )
    {

    }

    /**
     * @throws InvalidArgumentException
     */
    public function applyLowestPricePromotion(int $id, LowestPriceEnquiry $lowestPriceEnquiry): PriceEnquiryInterface
    {
        $product = $this->repository->findOrFail($id);

        $lowestPriceEnquiry->setProduct($product);

        $promotions = $this->promotionCache->findValidForProduct($product, $lowestPriceEnquiry->getRequestDate());

        return $this->promotionsFilter->apply($lowestPriceEnquiry, ...$promotions);
    }
}
