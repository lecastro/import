<?php

declare(strict_types=1);

namespace App\Domain\Repositories\Eloquent;

use App\Models\UploadHistory;

class UploadHistoryRepository extends BaseRepository
{
    /** @var UploadHistory */
    protected $model;

    public function __construct(UploadHistory $model)
    {
        parent::__construct($model);
    }
}
