<?php

declare(strict_types=1);

namespace App\Domain\Components\Output;

use Illuminate\Support\LazyCollection;
use App\Domain\Components\Helpers\DateHelper;
use App\Domain\Components\Helpers\StringHelper;

class TeamFileOutPut
{
    protected LazyCollection $collection;

    protected string $delimiter = ';';

    public function __construct(LazyCollection $collection)
    {
        $this->collection = $collection;
    }

    /** @return array[] */
    public function processFileInRows(): array
    {
        $data = [];

        foreach ($this->collection as $line) {
            $data[] = $this->adaptLines(
                $this->explodeLine($line[0])
            );
        }

        return $data;
    }

    /** @return array[] */
    private function explodeLine(string $line): array
    {
        return StringHelper::explode($this->delimiter, $line);
    }

    /**
     * @param string[] $line
     * @return string[]
     */
    private function adaptLines(array $line): array
    {
        return  [
            'cnpj'                          => $line[0] ?? '',
            'matricula'                     => $line[1] ?? '',
            'linha'                         => $line[2] ?? '',
            'matricula_gerente_de_vendas'   => $line[3] ?? '',
            'matricula_gerente_de_regional' => $line[4] ?? '',
            'created_at'                    => DateHelper::dateNow(),
            'updated_at'                    => DateHelper::dateNow()
        ];
    }
}
