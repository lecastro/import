<?php

declare(strict_types=1);

namespace App\Domain\Repositories\Eloquent;

use App\Models\TeamTest;

class TeamRepository extends BaseRepository
{
    /** @var TeamTest */
    protected $model;

    public function __construct(TeamTest $model)
    {
        parent::__construct($model);
    }
}
