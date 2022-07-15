<?php

declare(strict_types=1);

namespace App\Jobs;

use Throwable;
use App\Models\User;
use App\Models\View;
use App\Models\Place;
use App\Traits\CacheRedis;
use App\Models\BusinessLine;
use App\Jobs\ProcessBaseJobs;
use Illuminate\Contracts\Queue\ShouldQueue;

class StartCacheTeamsJobs extends ProcessBaseJobs implements ShouldQueue
{
    use CacheRedis;

    public $tries = 3;

    public function handle(): void
    {
        $this->cacheUserIds(User::KEY_CACHE);
        $this->cacheCnpj(Place::KEY_CACHE);
        $this->cacheViews(View::KEY_CACHE);
        $this->cacheBusinesslines(BusinessLine::KEY_CACHE);
    }

    public function failed(Throwable $exception): void
    {
        LoggerFacade::info(
            Team::GROUP_LOGGER,
            'falha inicia cache teams',
            [
                'mensagem'  => $exception->getMessage(),
                'erro'      => $exception
            ]
        );
    }
}
