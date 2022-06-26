<?php

declare(strict_types=1);

namespace App\Domain\Repositories\Eloquent;

use App\Models\Place;

class PlaceRepository extends BaseRepository
{
    /** @var Place */
    protected $model;

    public function __construct(Place $model)
    {
        parent::__construct($model);
    }

    /** @return Place[] */
    public function findAllByIdAndCnpj(): array
    {
        return $this->model
            ->newQuery()
            ->get(['id', 'cnpj'])
            ->all();
    }
}
