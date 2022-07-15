<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Repositories\Eloquent\TeamRepository;

class TeamService extends BaseServices
{
    protected TeamRepository $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    /** @param string[] */
    public function create(array $teams): void
    {
        $this->teamRepository->create($teams);
    }
}
