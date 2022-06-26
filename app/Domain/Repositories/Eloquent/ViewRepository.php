<?php

declare(strict_types=1);

namespace App\Domain\Repositories\Eloquent;

use App\Models\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class ViewRepository extends BaseRepository
{
    /** @var View */
    protected $model;

    public function __construct(View $model)
    {
        parent::__construct($model);
    }

    public function getAllPeopleWithViews(): EloquentCollection
    {
        return $this->model
            ->newQuery()
            ->select(
                'people_views.person_id',
                'views.parent_id as view_id',
                'views.id as sub_view_id'
            )
            ->join('people_views', 'people_views.view_id', 'views.id')
            ->whereNotNull('parent_id')
            ->groupBy('people_views.person_id')
            ->get();
    }
}
