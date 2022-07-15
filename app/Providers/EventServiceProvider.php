<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\DispatchExportableEvent;
use App\Events\InjectAllCnpjInCacheEvent;
use App\Events\InjectAllUserIdsInCacheEvent;
use App\Listeners\DispatchExportableListener;
use App\Listeners\InjectAllCnpjInCacheListener;
use App\Events\InjectAllBusinesslinesInCacheEvent;
use App\Listeners\InjectAllUserIdsInCacheListener;
use App\Events\InjectAllPeopleWithViewsIdsInCacheEvent;
use App\Listeners\InjectAllBusinesslinesInCacheListener;
use App\Listeners\InjectAllPeopleWithViewsIdsInCacheListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        InjectAllUserIdsInCacheEvent::class => [
            InjectAllUserIdsInCacheListener::class,
        ],
        InjectAllCnpjInCacheEvent::class => [
            InjectAllCnpjInCacheListener::class,
        ],
        InjectAllPeopleWithViewsIdsInCacheEvent::class => [
            InjectAllPeopleWithViewsIdsInCacheListener::class,
        ],
        InjectAllBusinesslinesInCacheEvent::class => [
            InjectAllBusinesslinesInCacheListener::class,
        ],
        DispatchExportableEvent::class => [
            DispatchExportableListener::class,
        ]
    ];
}
