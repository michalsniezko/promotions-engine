<?php

namespace App\Filter\Modifier\Factory;

use App\Filter\Modifier\PriceModifierInterface;
use Symfony\Component\VarExporter\Exception\ClassNotFoundException;

class PriceModifierFactory implements PriceModifierFactoryInterface
{
    /**
     * @throws ClassNotFoundException
     */
    public function create(string $modifierType): PriceModifierInterface
    {
        $modifierClassBaseName = str_replace('_', '', ucwords($modifierType, '_'));
        $modifier = self::PRICE_MODIFIER_NAMESPACE . $modifierClassBaseName;

        if(!class_exists($modifier)) {
            throw new ClassNotFoundException("Modifier class $modifier not found");
        }

        return new $modifier();
    }
}
