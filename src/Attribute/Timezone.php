<?php
declare(strict_types=1);

namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Timezone
{
    public function __construct(public string $default = 'UTC')
    {
    }
}
