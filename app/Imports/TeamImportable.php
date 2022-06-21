<?php

declare(strict_types=1);

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class TeamImportable extends BaseImportable implements
    ToCollection,
    WithHeadingRow,
    WithCustomCsvSettings,
    WithChunkReading
{
    /** @var int */
    protected static $TOTAL_SIZE = 1000;

    public function collection(Collection $collection)
    {
        //
    }

    public function chunkSize(): int
    {
        return self::$TOTAL_SIZE;
    }

    public function batchSize(): int
    {
        return self::$TOTAL_SIZE;
    }

    /** @return array[] */
    public function rules(): array
    {
        return [
            'cnpj'                          => ['required', new Cnpj()],
            'matricula'                     => 'required',
            'linha'                         => 'required',
            'matricula_gerente_de_vendas'   => 'required',
            'matricula_gerente_de_regional' => 'required',
        ];
    }

    /** @return array[] */
    public function messages(): array
    {
        return [
            'cnpj.required'                          => 'O campo :attribute inválido.',
            'matricula.required'                     => 'O campo :attribute inválido.',
            'linha.required'                         => 'O campo :attribute inválido.',
            'matricula_gerente_de_vendas.required'   => 'O campo :attribute inválido.',
            'matricula_gerente_de_regional.required' => 'O campo :attribute inválido.',
        ];
    }
}
