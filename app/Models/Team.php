<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
    use SoftDeletes;

    /** @var string */
    protected $connection = 'mysqlSchedule';

    /** @var int */
    public const DATETIME_PROCESS_CACHE = 86400;

    /** @var string */
    public const GROUP_LOGGER = 'Importação Carteira';

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

    public function businessLine(): BelongsTo
    {
        return $this->belongsTo(BusinessLine::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'manager_id');
    }

    public function viewManager(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'view_manager_id');
    }

    public function people(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'people_id');
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}
