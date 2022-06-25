<?php

declare(strict_types=1);

namespace App\Domain\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Repositories\Eloquent\Contract\BaseRepositoryContract;

class BaseRepository implements BaseRepositoryContract
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /** @param array $attributes*/
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
    * @param $id
    *
    * @return Model
    */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @param mixed[] $attributes
     * @param mixed[] $values
     * */
    public function firstOrCreate(array $attributes, array $values): Model
    {
        return $this->model->firstOrCreate(attributes: $attributes, values: $values);
    }

    /**
     * @param mixed[] $attributes
     * @param mixed[] $values
     * */
    public function updateOrCreate(array $attributes, array $values): Model
    {
        return $this->model->updateOrCreate(attributes: $attributes, values: $values);
    }
}