<?php

declare(strict_types=1);

namespace app\Traits;

use Event;
use Illuminate\Support\Collection;
use App\Domain\Components\Redis\Cache;
use Modules\Schedule\Events\InjectAllCnpjInCacheEvent;
use Modules\Schedule\Events\InjectAllUserIdsInCacheEvent;
use Modules\Schedule\Events\InjectAllBusinesslinesInCacheEvent;
use Modules\Schedule\Events\InjectAllPeopleWithViewsIdsInCacheEvent;

trait CacheRedis
{
    private function validate(string $key): ?Collection
    {
        if (Cache::has($key)) {
            return Cache::get($key);
        }

        return null;
    }

    protected function cacheUserIds(string $key): Collection
    {
        $cache = $this->validate($key);

        if ($cache) {
            return $cache;
        }

        Event::dispatch(new InjectAllUserIdsInCacheEvent());

        return Cache::get($key);
    }

    protected function cacheCnpj(string $key): Collection
    {
        $cache = $this->validate($key);

        if ($cache) {
            return $cache;
        }

        Event::dispatch(new InjectAllCnpjInCacheEvent());

        return Cache::get($key);
    }

    protected function cacheViews(string $key): Collection
    {
        $cache = $this->validate($key);

        if ($cache) {
            return $cache;
        }

        Event::dispatch(new InjectAllPeopleWithViewsIdsInCacheEvent());

        return Cache::get($key);
    }

    protected function cacheBusinesslines(string $key): Collection
    {
        $cache = $this->validate($key);

        if ($cache) {
            return $cache;
        }

        Event::dispatch(new InjectAllBusinesslinesInCacheEvent());

        return Cache::get($key);
    }
}
