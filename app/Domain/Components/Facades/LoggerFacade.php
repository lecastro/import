<?php

declare(strict_types=1);

namespace App\Domain\Components\Facades;

use Illuminate\Support\Facades\Facade;
use App\Domain\Components\Logger\Contract\NoneLoggerContract;

class LoggerFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return NoneLoggerContract::class;
    }
}
