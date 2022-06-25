<?php

declare(strict_types=1);

namespace App\Listeners;

class InjectAllCnpjInCacheListener
{
    /** @var PlaceService */
    protected $placeService;

    public function __construct(PlaceService $placeService)
    {
        $this->placeService = $placeService;
    }

    public function handle(): void
    {
        $this->placeService->injectAllCnpjInCache();
    }
}
