<?php

declare(strict_types=1);

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

abstract class BaseImportable
{
    /** @param array[] */
    protected $validationErrors = [];

    /** @param array[] */
    protected $validatedRows = [];

    /**
     * @return array[]
     * @throws ValidationException
     */
    protected function validateRows(array $rows): array
    {
        $validatedRows = [];

        foreach ($rows as $key => $row) {
            try {
                $validatedRows[] =
                    Validator::make(
                        $row,
                        $this->rules(),
                        $this->messages()
                    )->validate();
            } catch (ValidationException $e) {
                $this->sendValidationErrors(
                    $e->errors(),
                    $key + 2
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

    public function startTransaction(string $database): void
    {
        DB::connection($database)->beginTransaction();
    }

    public function commitTransaction(string $database): void
    {
        DB::connection($database)->commit();
    }

    public function rollbackTransaction(string $database): void
    {
        DB::connection($database)->rollback();
    }

    /** @return string[] */
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    /** @return string[] */
    abstract public function rules(): array;

    /** @return string[] */
    abstract public function messages(): array;
}
