<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Domain\Services\BusinessLineService;

class InjectAllBusinesslinesInCacheListener
{
    protected BusinessLineService $businessLineService;

    public function __construct(BusinessLineService $businessLineService)
    {
        $this->businessLineService = $businessLineService;
    }

    public function handle(): void
    {
        $this->businessLineService->injectBusinesslineCache();
    }
}
