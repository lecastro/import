<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Person extends Model
{
    /** @var string */
    protected $connection = 'mysql';

    /** @var string */
    public $table = 'people';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
