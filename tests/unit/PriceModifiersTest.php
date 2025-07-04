<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Filter\Modifier\DateRangeMultiplier;
use App\Filter\Modifier\EvenItemsMultiplier;
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

    #[Test]
    public function EvenItemsMultiplierReturnsCorrectlyModifiedPrice(): void
    {
        // given
        $enquiry = new LowestPriceEnquiry();
        $enquiry->setQuantity(5);

        $promotion = new Promotion();
        $promotion->setName('Buy one get one free');
        $promotion->setAdjustment(0.5);
        $promotion->setCriteria(['minimum_quantity' => 2]);
        $promotion->setType('even_items_multiplier');

        $evenItemsMultiplier = new EvenItemsMultiplier();

        // when
        $modifiedPrice = $evenItemsMultiplier->modify(100, 5, $promotion, $enquiry);

        // then
        // ( (100 * 4) * 0.5 ) + (1 * 100) = 300
        $this->assertEquals(300, $modifiedPrice);
    }

    #[Test]
    public function EvenItemsMultiplierReturnsCorrectlyCalculatesAlternatives(): void
    {
        // given
        $enquiry = new LowestPriceEnquiry();
        $enquiry->setQuantity(5);

        $promotion = new Promotion();
        $promotion->setName('Buy one get half price');
        $promotion->setAdjustment(0.75);
        $promotion->setCriteria(['minimum_quantity' => 2]);
        $promotion->setType('even_items_multiplier');

        $evenItemsMultiplier = new EvenItemsMultiplier();

        // when
        $modifiedPrice = $evenItemsMultiplier->modify(100, 5, $promotion, $enquiry);

        // then
        // 300 + 100 = 400
        $this->assertEquals(400, $modifiedPrice);
    }
}