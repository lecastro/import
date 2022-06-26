<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Domain\Services\PlaceService;

class InjectAllCnpjInCacheListener
{
    protected PlaceService $placeService;

    public function __construct(PlaceService $placeService)
    {
        $this->placeService = $placeService;
    }

    public function handle(): void
    {
        $this->placeService->injectAllCnpjInCache();
    }
}
