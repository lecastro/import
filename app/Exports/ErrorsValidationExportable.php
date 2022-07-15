<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ErrorsValidationExportable implements FromArray, WithHeadings, WithCustomCsvSettings
{
    protected array $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    /** @return array[] */
    public function array(): array
    {
        $data  = [];

        foreach ($this->errors as $key => $errors) {
            foreach ($errors as $error) {
                $data[] = [
                    $key,
                    $error[0]
                ];
            }
        }

        return [
            $data
        ];
    }

    /** @return array[] */
    public function headings(): array
    {
        return [
            'Linha',
            'Erro'
        ];
    }

    /** @return array[] */
    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'UTF-8',
            'delimiter'      => ';'
        ];
    }
}
