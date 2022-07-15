<?php

declare(strict_types=1);

namespace App\Domain\Components\Facades;

use Illuminate\Support\Facades\Facade;
use App\Domain\Components\Redis\CacheExportable;

class CacheExportableFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CacheExportable::class;
    }
}
