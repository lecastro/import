<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Person;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model
{
    /** @var int */
    public const TIME_CACHE = 28800;

    /** @var string */
    public const KEY_CACHE =  'ids';

    /** @var string */
    protected $connection = 'mysql';

    /** @var string */
    public $table = 'users';

    public function person(): HasOne
    {
        return $this->hasOne(Person::class, 'user_id');
    }
}
