<?php
declare(strict_types=1);

namespace App\ArgumentResolver;

use App\Attribute\Timezone;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class TimezoneValueResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        $timezoneAttribute = $this->getTimezoneAttribute($argument);
        if ($timezoneAttribute === null) {
            return [];
        }

        return [$request->query->get('timezone', $timezoneAttribute->default)];
    }

    private function getTimezoneAttribute(ArgumentMetadata $argument): ?Timezone
    {
        foreach ($argument->getAttributes() as $attribute) {
            if ($attribute instanceof Timezone) {
                return $attribute;
            }
        }

        return null;
    }
}
