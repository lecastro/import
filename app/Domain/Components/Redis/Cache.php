<?php

declare(strict_types=1);

namespace App\Domain\Components\Redis;

use Illuminate\Support\Facades\Cache as BaseCache;
use Illuminate\Support\Facades\Log;

class Cache
{
    /** @param mixed $data */
    public static function forever(string $key, $data): void
    {
        try {
            BaseCache::forever($key, $data);
        } catch (\Exception $e) {
            Log::error('Cache Redis Forever', ['error' => $e->getMessage()]);
        }
    }

    public static function has(string $key): bool
    {
        try {
            return BaseCache::has($key);
        } catch (\Exception $e) {
            Log::error('Cache Redis Has', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /** @return mixed $data */
    public static function get(string $key)
    {
        try {
            return BaseCache::get($key);
        } catch (\Exception $e) {
            Log::error('Cache Redis Get', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /** @param mixed $data */
    public static function put(string $key, $data, int $time): void
    {
        try {
            BaseCache::put($key, $data, $time);
        } catch (\Exception $e) {
            Log::error('Cache Redis Put', ['error' => $e->getMessage()]);
        }
    }

    public static function forget(string $key): void
    {
        try {
            BaseCache::forget($key);
        } catch (\Exception $e) {
            Log::error('Cache Redis Forget', ['error' => $e->getMessage()]);
        }
    }
}
