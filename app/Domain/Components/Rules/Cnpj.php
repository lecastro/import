<?php

namespace App\Domain\Components\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Domain\Components\Helpers\ValidatorHelper;

class Cnpj implements Rule
{
    private const notValidErrorMsg = "CNPJ inválido";

    /**
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (ValidatorHelper::validateCnpj($attribute, $value)) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return self::notValidErrorMsg;
    }
}
