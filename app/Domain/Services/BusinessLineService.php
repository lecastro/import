<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Models\BusinessLine;
use App\Domain\Components\Redis\Cache;
use App\Domain\Repositories\Eloquent\BusinessLineRepository;
use App\Domain\Services\BaseServices;

class BusinessLineService extends BaseServices
{
    protected BusinessLineRepository $businessLineRepository;

    public function __construct(BusinessLineRepository $businessLineRepository)
    {
        $this->businessLineRepository = $businessLineRepository;
    }

    public function injectBusinesslineCache(): void
    {
        $businesslines = $this->businessLineRepository->get();

        $data = [];

        foreach ($businesslines as $businessline) {
            $data[$businessline->name] = $businessline->id;
        }

        Cache::put(
            BusinessLine::KEY_CACHE,
            collect($data),
            BusinessLine::TIME_CACHE
        );
    }
}
