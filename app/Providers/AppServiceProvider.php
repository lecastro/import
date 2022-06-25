<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providers\LoggerServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(LoggerServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
