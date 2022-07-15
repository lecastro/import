<?php

declare(strict_types=1);

namespace App\Models;

use Modules\Schedule\Entities\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Place extends Model
{
    use SoftDeletes;

    public const KEY_CACHE = 'cnpj';

    /** @var int */
    public const TIME_CACHE = 28800;

    protected $connection = 'mysql';

    protected $fillable = [
        'name',
        'cnpj',
        'company_name',
        'address_id',
        'matriz',
        'email',
        'nome_contato',
        'places_groups_id',
        'places_attendances_id',
        'places_categories_id',
        'custcodes_id',
        'homepage',
        'by_user'
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(
            Address::class,
            'address_id'
        );
    }

    public function phones(): BelongsToMany
    {
        return $this->belongsToMany(
            Phone::class,
            'place_has_phone',
            'place_id',
            'phone_id'
        );
    }

    public function viewHasPlace(): HasMany
    {
        return $this->hasMany(ViewHasPlace::class, 'place_id');
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class, 'place_id');
    }
}
