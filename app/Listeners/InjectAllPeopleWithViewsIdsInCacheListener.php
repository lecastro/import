<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Domain\Services\ViewService;

class InjectAllPeopleWithViewsIdsInCacheListener
{
    protected ViewService $viewService;

    public function __construct(ViewService $viewService)
    {
        $this->viewService = $viewService;
    }

    public function handle(): void
    {
        $this->viewService->injectPeopleWithViewsCache();
    }
}
