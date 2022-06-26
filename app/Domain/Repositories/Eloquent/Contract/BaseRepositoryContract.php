<?php

declare(strict_types=1);

namespace App\Domain\Repositories\Eloquent\Contract;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Interface EloquentRepositoryContract
 * @package App\Repositories
 */
interface BaseRepositoryContract
{
    /**
     * @param array $attributes
     */
    public function create(array $attributes): Model;

    /**
     * @param $id
     * @return Model
     */
    public function find($id): ?Model;

    /**
     * @param $attributes
     * @param $values
     * @return Model
     */
    public function firstOrCreate(array $attributes, array $values): Model;


    /**
     * @param $attributes
     * @param $values
     * @return Model
     */
    public function updateOrCreate(array $attributes, array $values): Model;

    /** @param $values */
    public function createInBulk(array $attributes): void;

    public function get(): EloquentCollection;
}
