<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Models\User;
use App\Domain\Services\BaseServices;
use App\Domain\Components\Redis\Cache;
use App\Domain\Repositories\Eloquent\UserRepository;

class UserService extends BaseServices
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function injectAllUserIdsInCache(): void
    {
        $users = $this->userRepository->getAllUsersWithPerson(
            ['id', 'registration']
        );

        $data = [];

        foreach ($users as $user) {
            if (empty($user->person)) {
                continue;
            }

            $data[(string) $user->registration] = $user->person->id;
        }

        Cache::put(
            User::KEY_CACHE,
            collect($data),
            User::TIME_CACHE
        );
    }
}
