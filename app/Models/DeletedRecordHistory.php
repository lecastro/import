<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeletedRecordHistory extends Model
{
    use SoftDeletes;

    /** @var string */
    public const PROCESSED = 'processed';

    /** @var string */
    protected $connection = 'mysqlSchedule';

    /** @var string */
    protected $table = 'deleted_record_histories';

    protected $fillable = [
        'start_range',
        'final_range',
        'volume',
        'database',
        'table',
        'status'
    ];

    protected $dates = [
        'start_range',
        'final_range',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
