<?php

declare(strict_types=1);

namespace App\Imports;

use App\Traits\CacheRedis;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Domain\Components\Facades\LoggerFacade;

abstract class BaseImportable
{
    use CacheRedis;

    /** @return string[] */
    abstract public function rules(): array;

    /** @return string[] */
    abstract public function messages(): array;
    /**
     * @return array[]
     * @throws ValidationException
     */
    protected function validateRow(array $rows): array
    {
        $validatedRows  = [];

        foreach ($rows as $key => $row) {
            try {
                $validatedRows[] =
                    Validator::make(
                        $row,
                        $this->rules(),
                        $this->messages()
                    )->validate();
            } catch (ValidationException $e) {
                LoggerFacade::info(
                    'Erro De Validação',
                    'erro.',
                    [
                        'Erro'  => $e->errors(),
                        'linha' => $key + 2
                    ]
                );
            }
        }

        return $validatedRows;
    }

    /** @param array[] */
    protected function sendValidationErrors(array $errors, int $key): void
    {
        $this->validationErrors[$key] = $errors;
    }

    /** @return array[] */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    /** @param array[] | int */
    protected function sendSuceessFulLines($validatedRows): void
    {
        $this->validatedRows[] = $validatedRows;
    }

    /** @return array[] */
    public function getSuceessFulLines(): array
    {
        return $this->validatedRows;
    }

    protected function sanitizeCnpj(string $cnpj): string
    {
        return trim(preg_replace('/[^0-9]/', '', $cnpj));
    }

    protected function sanitizeRegistration(string $registration): string
    {
        return trim(str_replace('F', '', $registration));
    }

    protected function sanitizeSlug(string $slug): string
    {
        return mb_strtoupper(Str::slug($slug, '_'));
    }

    protected function sanitizeCpf(string $cpf): string
    {
        return trim(preg_replace('/[^0-9]/', '', $cpf));
    }

    protected function sanitizeArg(string $arg): string
    {
        return mb_strtoupper(Str::slug($arg, '_'));
    }
}
