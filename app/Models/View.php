<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class View extends Model
{
    use SoftDeletes;

    /** @var string */
    public const KEY_CACHE = 'viewsIds';

    /** @var int */
    public const TIME_CACHE = 28800;

    /** @var string */
    protected $connection = 'mysql';

    /** @var string */
    protected $table = 'views';

    /** @var string[] */
    protected $fillable = [
        'name',
        'slug',
        'parent_id'
    ];

    /** @var string[] */
    protected $hidden = [
        'deleted_at',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(View::class, 'parent_id');
    }

    public function subViews(): HasMany
    {
        return $this->hasMany(View::class, 'parent_id');
    }

    public function viewHasPlace(): HasMany
    {
        return $this->hasMany(ViewHasPlace::class, 'view_id');
    }
}
