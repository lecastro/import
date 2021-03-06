<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Team;
use App\Traits\CacheRedis;
use App\Domain\Components\Rules\Cnpj;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Domain\Components\Facades\LoggerFacade;
use App\Domain\Components\Facades\CacheExportableFacade;

abstract class BaseImportable
{
    use CacheRedis;

    /**
     * @return array[]
     * @throws ValidationException
     */
    protected function validateRow(array $rows): array
    {
        $validatedRows = [];
        $errors        = null;

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
                    Team::GROUP_LOGGER,
                    'erro de validacao',
                    [
                        'Erro'  => $e->errors(),
                        'linha' => $key + 2
                    ]
                );

                $errors = $this->errors(
                    $e->errors(),
                    $key + 2
                );

                $this->generateCacheRowsErrors($errors);

                continue;
            }
        }

        return $validatedRows;
    }

    /** @param array[] $errors */
    protected function generateCacheRowsErrors(array $errors): void
    {
        if (CacheExportableFacade::check(Team::VALIDATION_ERRORS, $errors)) {
            return;
        }

        CacheExportableFacade::push(
            Team::VALIDATION_ERRORS,
            $errors
        );
    }

    /** @param array[] $validatedRows */
    protected function generateCacheRowsSuccessfully(array $validatedRows): void
    {
        if (CacheExportableFacade::check(Team::VALIDATION_SUCCESSFULLY, $validatedRows)) {
            return;
        }

        CacheExportableFacade::push(
            Team::VALIDATION_SUCCESSFULLY,
            $validatedRows
        );
    }

    /**
     * @param array[] $errors
     * @return array[]
     */
    protected function errors(array $errors, int $key): array
    {
        return [$errors[$key] = $errors];
    }

    /** @return array[] */
    public function rules(): array
    {
        return [
            'cnpj'                          => ['required', new Cnpj()],
            'registration'                  => "required|string|exists:users,registration",
            'line'                          => 'required|string|exists:mysqlSchedule.business_lines,name',
            'registration_sales_manager'    => 'required|string|exists:users,registration',
            'registration_view_manager'     => 'required|string|exists:users,registration',
        ];
    }

    /** @return array[] */
    public function messages(): array
    {
        return [
            'cnpj.required'                         => 'O campo :attribute inv??lido',
            'registration.required'                 => 'O campo :attribute inv??lido',
            'linha.required'                        => 'O campo :business_lines inv??lido',
            'registration_sales_manager.required'   => 'O campo :attribute inv??lido',
            'registration_view_manager.required'    => 'O campo :attribute inv??lido',
        ];
    }
}
