<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiry;
use App\Event\AfterDtoCreatedEvent;
use App\Tests\ServiceTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class DtoSubscriberTest extends ServiceTestCase
{
    #[Test]
    public function testDtoIsValidatedAfterItHasBeenCreated()
    {
        // given
        $dto = new LowestPriceEnquiry();
        $dto->setQuantity(-5);
        $event = new AfterDtoCreatedEvent($dto);
        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $this->container->get('event_dispatcher');

        // expect
        $this->expectException(ValidationFailedException::class);
        $this->expectExceptionMessage('This value should be positive.');

        // when
        $eventDispatcher->dispatch($event, AfterDtoCreatedEvent::NAME);
    }
}
