<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationExceptionData extends ServiceExceptionData
{
    private ConstraintViolationListInterface $violations;

    public function __construct(int $statusCode, string $type, ConstraintViolationListInterface $violations)
    {
        parent::__construct($statusCode, $type);

        $this->violations = $violations;
    }

    public function toArray(): array
    {
        return [
            'type' => 'ConstraintViolationList',
            'violations' => $this->getViolationsArray(),
        ];
    }

    public function getViolationsArray(): array
    {
        $violations = [];

        /** @var ConstraintViolationListInterface $violation */
        foreach ($this->violations as $violation) {
            $violations[] = [
                'propertyPath' => $violation->getPropertyPath(),
                'message' => $violation->getmessage(),
            ];
        }

        return $violations;
    }
}
