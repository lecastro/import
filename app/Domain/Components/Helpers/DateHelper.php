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

    public static function formatDate(Carbon $date): string
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    public static function formatDateTime(Carbon $dateTime): string
    {
        return Carbon::parse($dateTime)->format('Y-m-d H:i:s');
    }

    public static function year(): string
    {
        return Carbon::now()->format('Y');
    }
}
