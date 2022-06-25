<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Team;
use Illuminate\Support\Collection;
use App\Domain\Services\TeamService;
use App\Domain\Components\Rules\Cnpj;
use App\Domain\Components\Facades\LoggerFacade;

class TeamImportable extends BaseImportable
{
    /** @var TeamService */
    protected $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function importProcess(array $team): void
    {
        LoggerFacade::info(
            Team::GROUP_LOGGER,
            'Iniciando Importação Carteira.',
            ['Carteira' => $team]
        );

        $validatedRows = $this->validateRow($team);

        try {
        } catch (Throwable $th) {
            LoggerFacade::warning(
                Team::GROUP_LOGGER,
                'Erro ao processar importação.',
                ['error' => $th->getMessage()]
            );
            throw $th;
        }
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
