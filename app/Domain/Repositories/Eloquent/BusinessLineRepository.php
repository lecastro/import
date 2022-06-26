<?php

declare(strict_types=1);

namespace App\Domain\Repositories\Eloquent;

use App\Models\BusinessLine;

class BusinessLineRepository extends BaseRepository
{
    /** @var BusinessLine */
    protected $model;

    public function __construct(BusinessLine $model)
    {
        parent::__construct($model);
    }
}
