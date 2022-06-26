<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Domain\Services\UserService;

class InjectAllUserIdsInCacheListener
{
    /** @var UserService */
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function handle(): void
    {
        $this->userService->injectAllUserIdsInCache();
    }
}
