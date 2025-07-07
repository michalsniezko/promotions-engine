<?php

namespace App\Cache;

use App\Entity\Product;
use App\Repository\PromotionRepository;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;

class PromotionCache
{
    public function __construct(private CacheInterface $cache, private PromotionRepository $promotionRepository)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function findValidForProduct(Product $product, string $requestDate): ?array
    {
        $key = sprintf("valid-for-product-%s", $product->getId());

        return $this->cache->get($key, function () use ($product, $requestDate) {
            return $this->promotionRepository->findValidForProduct(
                $product,
                date_create_immutable($requestDate)
            );
        }
        );
    }
}
