<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Exceptions\BusinessExceptions\BusinessRuleExceptions;
use Illuminate\Http\Response;

class CsvNotFoundException extends BusinessRuleExceptions
{
    /**
     * @return string
     */
    public function getShortMessage(): string
    {
        return 'CsvNotFoundException';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return trans('exception.csv.invalid');
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
