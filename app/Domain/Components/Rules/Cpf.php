<?php

declare(strict_types=1);

namespace App\Domain\Components\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Domain\Components\Helpers\ValidatorHelper;

class Cpf implements Rule
{
    private const notValidErrorMsg = "CPF inválido";

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (ValidatorHelper::validateCpf($attribute, $value)) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function message()
    {
        return self::notValidErrorMsg;
    }
}
