<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Components\Logger\Logger;
use App\Domain\Components\Logger\Contract\NoneLoggerContract;

class LoggerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NoneLoggerContract::class, Logger::class);
    }

    public function provides(): array
    {
        return [
            NoneLoggerContract::class,
            Logger::class
        ];
    }

    public function isDeferred(): bool
    {
        return true;
    }
}
