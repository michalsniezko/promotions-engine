<?php

namespace App\Cache;

use App\Entity\Product;
use App\Repository\PromotionRepository;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class PromotionCache
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly PromotionRepository $promotionRepository
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function findValidForProduct(Product $product, string $requestDate): ?array
    {
        $key = sprintf("valid-for-product-%s", $product->getId());

        return $this->cache->get($key, function (ItemInterface $item) use ($product, $requestDate) {
            $item->expiresAfter(5);
            return $this->promotionRepository->findValidForProduct(
                $product,
                date_create_immutable($requestDate)
            );
        }
        );
    }
}
