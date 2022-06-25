<?php

declare(strict_types=1);

namespace App\Domain\Components\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function dateNow(): Carbon
    {
        return Carbon::now();
    }
}
