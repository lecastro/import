<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Models\Place;
use App\Domain\Services\BaseServices;
use App\Domain\Components\Redis\Cache;
use App\Domain\Repositories\Eloquent\PlaceRepository;

class PlaceService extends BaseServices
{
    protected PlaceRepository $placeRepository;

    public function __construct(PlaceRepository $placeRepository)
    {
        $this->placeRepository = $placeRepository;
    }

    public function injectAllCnpjInCache(): void
    {
        $places = $this->placeRepository->findAllByIdAndCnpj();

        $data = [];

        foreach ($places as $place) {
            $data[(string) $place->cnpj] = $place->id;
        }

        Cache::put(
            Place::KEY_CACHE,
            collect($data),
            Place::TIME_CACHE
        );
    }
}
