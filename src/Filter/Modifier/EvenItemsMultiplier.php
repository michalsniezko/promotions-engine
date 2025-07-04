<?php

namespace App\Filter\Modifier;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;
use App\Filter\Modifier\PriceModifierInterface;

class EvenItemsMultiplier implements PriceModifierInterface
{

    public function modify(int $price, int $quantity, Promotion $promotion, PromotionEnquiryInterface $enquiry): int
    {
        if ($quantity < 2) {
            return $price * $quantity;
        }

        // get the odd item if there is one
        $oddCount = $quantity % 2; // 0 or 1

        // how many even items
        $evenCount = $quantity - $oddCount;

        return (($price * $evenCount) * $promotion->getAdjustment()) + ($oddCount * $price);
    }
}
