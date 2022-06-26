<?php

declare(strict_types=1);

namespace App\Domain\Repositories\Eloquent;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class UserRepository extends BaseRepository
{
    /** @var User */
    protected $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /** @param users[] */
    public function getAllUsersWithPerson(array $all = ['*']): EloquentCollection
    {
        return $this->model
            ->newQuery()
            ->with('person')
            ->get($all);
    }
}
