<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Exceptions\BusinessExceptions\BusinessRuleExceptions;
use Illuminate\Http\Response;

class CacheUsersNotFoundException extends BusinessRuleExceptions
{
    public function getShortMessage(): string
    {
        return 'CachePlaceNotFoundException';
    }

    public function getDescription(): string
    {
        return trans('exception.cache.users');
    }

    public function getHttpStatus(): int
    {
        return Response::HTTP_NOT_FOUND;
    }
}
