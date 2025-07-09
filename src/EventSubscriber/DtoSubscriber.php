<?php

namespace App\EventSubscriber;

use App\Event\AfterDtoCreatedEvent;
use App\Service\ServiceException;
use App\Service\ValidationExceptionData;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterDtoCreatedEvent::NAME => 'validateDto'
        ];
    }

    public function validateDto(AfterDtoCreatedEvent $event): void
    {
        $dto = $event->getDto();
        $errors = $this->validator->validate($dto);

        if ($errors->count() > 0) {
            $validationExceptionData = new ValidationExceptionData(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'ConstraintViolationList',
                $errors
            );

            throw new ServiceException($validationExceptionData);
        }
    }
}
