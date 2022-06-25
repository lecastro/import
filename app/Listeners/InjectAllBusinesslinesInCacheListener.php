<?php

declare(strict_types=1);

namespace App\Listeners;

class InjectAllBusinesslinesInCacheListener
{
    /** @var BusinessLineService */
    protected $businessLineService;

    public function __construct(BusinessLineService $businessLineService)
    {
        $this->businessLineService = $businessLineService;
    }

    public function handle(): void
    {
        $this->businessLineService->injectBusinesslineCache();
    }
}
