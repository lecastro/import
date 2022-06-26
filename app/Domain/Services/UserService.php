<?php

declare(strict_types=1);

namespace App\Domain\Services;

use Modules\Core\Entities\User;
use App\Domain\Components\Redis\Cache;
use App\Domain\Repositories\Eloquent\UserRepository;
use App\Domain\Services\BaseServices;

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
        dd($data);
        Cache::put(
            User::KEY_CACHE,
            collect($data),
            User::TIME_CACHE
        );
    }
}
