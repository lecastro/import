<?php

declare(strict_types=1);

namespace App\Listeners;

class InjectAllPeopleWithViewsIdsInCacheListener
{
    /** @var ViewService */
    protected $viewService;

    public function __construct(ViewService $viewService)
    {
        $this->viewService = $viewService;
    }

    public function handle(): void
    {
        $this->viewService->injectPeopleWithViewsCache();
    }
}
