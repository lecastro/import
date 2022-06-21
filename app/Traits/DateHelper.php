<?php

declare(strict_types=1);

namespace app\Traits;

use Carbon\Carbon;

trait DateHelper
{
    public function formatDate(Carbon $date): string
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    public function formatDateTime(Carbon $dateTime): string
    {
        return Carbon::parse($dateTime)->format('Y-m-d H:i:s');
    }

    public function dateNow(): string
    {
        return Carbon::now()->format('Y-m-d');
    }
}
