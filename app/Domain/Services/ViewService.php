<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Models\View;
use App\Domain\Components\Redis\Cache;
use App\Domain\Repositories\Eloquent\ViewRepository;

class ViewService
{
    protected ViewRepository $viewRepository;

    public function __construct(ViewRepository $viewRepository)
    {
        $this->viewRepository = $viewRepository;
    }

    /** @return View[] */
    public function getAllViews(): array
    {
        return $this->coreViewService->findAll();
    }

    /** @return mixed [] */
    public function injectPeopleWithViewsCache()
    {
        $personWithViewsIds = $this->viewRepository->getAllPeopleWithViews();

        $data = [];

        foreach ($personWithViewsIds as $personWithViewsId) {
            $data[$personWithViewsId->person_id] = [
                'view_id'       => $personWithViewsId->view_id,
                'sub_view_id'   => $personWithViewsId->sub_view_id
            ];
        }

        Cache::put(
            View::KEY_CACHE,
            collect($data),
            View::TIME_CACHE
        );

        return $data;
    }
}
