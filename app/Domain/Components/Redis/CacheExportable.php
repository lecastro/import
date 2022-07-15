<?php

declare(strict_types=1);

namespace App\Domain\Components\Redis;

use App\Domain\Components\Redis\Cache;

class CacheExportable
{
    /** @var int */
    public const EXPIRATION = 86400;

    public function check(string $key, array $values): bool
    {
        if (!Cache::has($key)) {
            Cache::put(
                $key,
                $values,
                SELF::EXPIRATION
            );

            return true;
        }

        return false;
    }

    /** @param array[] $errors*/
    public function push(string $key, array $currentErros): void
    {
        $accumulatedErros = Cache::get($key);

        Cache::put(
            $key,
            $this->mergeErros($currentErros, $accumulatedErros),
            SELF::EXPIRATION
        );
    }

    /**
     * @param array[] $currentErros
     * @param array[] $accumulatedErros
     * @return array[]
     */
    public function mergeErros(array $currentErros, array $accumulatedErros): array
    {
        return array_merge($currentErros, $accumulatedErros);
    }
}
