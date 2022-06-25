<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamTest extends Model
{
    use SoftDeletes;

    /** @var string */
    protected $connection = 'mysqlSchedule';

    /** @var string */
    protected $table = 'teamsTest';

    /** @var int */
    public const DATETIME_PROCESS_CACHE = 86400;

    /** @var string[] */
    protected $fillable = [
        'id',
        'business_line_id',
        'manager_id',
        'people_id',
        'place_id',
        'view_manager_id',
        'view_id',
        'sub_view_id',
        'created_manually'
    ];
}
