<?php

declare(strict_types=1);

namespace App\Domain\Components\Helpers;

class StringHelper
{
    public static function explode(
        string $delemiter,
        string $value
    ): array {
        return explode($delemiter, $value);
    }
}
