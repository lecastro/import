<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\InjectAllCnpjInCacheEvent;
use App\Events\InjectAllUserIdsInCacheEvent;
use App\Listeners\InjectAllCnpjInCacheListener;
use App\Events\InjectAllBusinesslinesInCacheEvent;
use App\Listeners\InjectAllUserIdsInCacheListener;
use App\Events\InjectAllPeopleWithViewsIdsInCacheEvent;
use App\Listeners\InjectAllBusinesslinesInCacheListener;
use App\Listeners\InjectAllPeopleWithViewsIdsInCacheListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array[]
     */
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
        ]
    ];
}
