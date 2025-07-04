<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Filter\Modifier\DateRangeMultiplier;
use App\Filter\Modifier\FixedPriceVoucher;
use App\Tests\ServiceTestCase;
use PHPUnit\Framework\Attributes\Test;

class PriceModifiersTest extends ServiceTestCase
{
    #[Test]
    public function DateRangeMultiplierReturnsCorrectlyModifiedPrice(): void
    {
        // given
        $dateRangeModifier = new DateRangeMultiplier();

        $promotion = new Promotion();
        $promotion->setName('Black Friday half price sale');
        $promotion->setAdjustment(0.5);
        $promotion->setCriteria(['from' => '2022-11-25', 'to' => '2022-11-28']);
        $promotion->setType('date_range_multiplier');

        $enquiry = new LowestPriceEnquiry();
        $enquiry->setQuantity(5);
        $enquiry->setRequestDate('2022-11-27');


        // when
        $modifiedPrice = $dateRangeModifier->modify(100, 5, $promotion, $enquiry);

        // then
        $this->assertEquals(250, $modifiedPrice);
    }

    #[Test]
    public function FixedPriceVoucherReturnsCorrectlyModifiedPrice(): void
    {
        $fixedPriceVoucher = new FixedPriceVoucher();

        $promotion = new Promotion();
        $promotion->setName('Voucher OU812');
        $promotion->setAdjustment(100);
        $promotion->setCriteria(['code' => 'OU812']);;
        $promotion->setType('fixed_price_voucher');;

        $enquiry = new LowestPriceEnquiry();
        $enquiry->setQuantity(5);
        $enquiry->setVoucherCode('OU812');

        $modifiedPrice = $fixedPriceVoucher->modify(150, 5, $promotion, $enquiry);
        $this->assertEquals(500, $modifiedPrice);
    }
}