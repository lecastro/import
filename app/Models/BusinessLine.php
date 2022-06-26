<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessLine extends Model
{
    /** @var string */
    public const KEY_CACHE = 'businesslineIds';

    /** @var int */
    public const TIME_CACHE = 86400;

    protected $connection = 'mysqlSchedule';

    protected $fillable = ['name'];

    public function teams()
    {
        return $this->hasMany(Team::class, 'business_line_id');
    }
}
