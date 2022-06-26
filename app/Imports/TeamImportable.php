<?php

declare(strict_types=1);

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Team;
use App\Models\User;
use App\Models\View;
use App\Models\Place;
use App\Models\BusinessLine;
use App\Imports\BaseImportable;
use Illuminate\Support\Collection;
use App\Domain\Services\TeamService;
use App\Domain\Components\Rules\Cnpj;
use App\Domain\Components\Facades\LoggerFacade;
use App\Domain\Components\Helpers\StringHelper;
use App\Exceptions\CachePlaceNotFoundException;
use App\Exceptions\CacheUsersNotFoundException;
use App\Exceptions\CacheViewsNotFoundException;
use App\Exceptions\CacheBusinessLineNotFoundException;

class TeamImportable extends BaseImportable
{
    protected TeamService $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function importProcessInRow(array $team): void
    {
        LoggerFacade::info(
            Team::GROUP_LOGGER,
            'Iniciando Importação Carteira.',
            ['Carteira' => $team]
        );

        $validatedRows = $this->validateRow($team);

        try {
            $this->processTeam(collect($validatedRows));
        } catch (Throwable $th) {
            LoggerFacade::error(
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

    private function processTeam(Collection $rows): void
    {
        $rows = $this->mapRows($rows);

        foreach ($rows as $row) {
            try {
                [
                    $personId,
                    $personIdSalesManager,
                    $personIdViewManager,
                    $place,
                    $businessline
                ] = $this->destructuringTeam($row);

                $validTeam = $this->validateTeam(
                    $personId,
                    $personIdSalesManager,
                    $personIdViewManager,
                    $place,
                    $businessline
                );

                if ($validTeam) {
                    $team =
                        $this->mountTeam(
                            $personId,
                            $personIdSalesManager,
                            $personIdViewManager,
                            $place,
                            $businessline
                        );

                    $this->teamService->createInBulk($team);

                    LoggerFacade::info(
                        Team::GROUP_LOGGER,
                        'Importação Carteira com sucesso.',
                        ['carteira'  => $team]
                    );
                }
            } catch (PlaceNotFoundException $ex) {
                LoggerFacade::error(
                    Team::GROUP_LOGGER,
                    'PDV (place) não encontrado em importação de carteira.',
                    [
                        'erro'      => $ex->getMessage(),
                        'carteira'  => $team
                    ]
                );

                continue;
            } catch (UserNotFoundException $ex) {
                LoggerFacade::info(
                    Team::GROUP_LOGGER,
                    'Usuário não encontrado em importação de carteira.',
                    [
                        'erro'      => $ex->getMessage(),
                        'carteira'  => $team
                    ]
                );

                continue;
            } catch (Exception $ex) {
                LoggerFacade::info(
                    Team::GROUP_LOGGER,
                    'Erro Processar Carteira.',
                    [
                        'erro'      => $ex->getMessage(),
                        'carteira'  => $team
                    ]
                );
            }
        }
    }

    /**
     * @param Collection
     * @return int[]
     */
    private function destructuringTeam($row): array
    {
        return [
            $this->getUserByRegistration(
                $row->get('registration')
            ),
            $this->getUserByRegistration(
                $row->get('registration_sales_manager')
            ),
            $this->getUserByRegistration(
                $row->get('registration_view_manager')
            ),
            $this->getPlaceByCnpj(
                $row->get('cnpj')
            ),
            $this->getByBusinessLine(
                $row->get('line')
            ),
        ];
    }

    private function validateTeam(
        ?int $registration,
        ?int $registrationSalesManager,
        ?int $registrationViewManager,
        ?int $place,
        ?int $businessline
    ): bool {
        return $registration
            && $registrationSalesManager
            && $registrationViewManager
            && $place
            && $businessline;
    }

    private function mapRows(Collection $rows): Collection
    {
        return $rows->map(function (array $row): Collection {
            return $this->mapRow(Collect($row));
        });
    }

    private function mapRow(Collection $row): Collection
    {
        return Collect([
            'registration'               => $this->sanitizeRegistration(
                (string) $row->get('matricula')
            ),
            'registration_sales_manager' => $this->sanitizeRegistration(
                (string) $row->get('matricula_gerente_de_vendas')
            ),
            'registration_view_manager'  => $this->sanitizeRegistration(
                (string) $row->get('matricula_gerente_de_regional')
            ),
            'cnpj'                       => $this->sanitizeCnpj(
                (string) $row->get('cnpj')
            ),
            'line'                       => StringHelper::make()->removeAccentsAndToUppercase(
                (string) $row->get('linha')
            ),
        ]);
    }

    /** @throws CacheUsersNotFoundException */
    private function getUserByRegistration(string $registration): ?int
    {
        $users = $this->cacheUserIds(User::KEY_CACHE);

        if (!$users) {
            throw new CacheUsersNotFoundException();
        }

        if ($users->has($registration)) {
            return $users->get($registration);
        }

        return null;
    }

    /** @throws CachePlaceNotFoundException */
    private function getPlaceByCnpj(string $cnpj): ?int
    {
        $places = $this->cacheCnpj(Place::KEY_CACHE);

        if (!$places || $places->isEmpty()) {
            throw new CachePlaceNotFoundException();
        }

        if ($places->has($cnpj)) {
            return $places->get($cnpj);
        }

        return null;
    }

    /**
     * @return int|void
     * @throws CacheViewsNotFoundException
     * */
    private function getViewsWithSubViewsByPersonId(int $person)
    {
        $personIdsWithViews = $this->cacheViews(View::KEY_CACHE);

        if (!$personIdsWithViews || $personIdsWithViews->isEmpty()) {
            throw new CacheViewsNotFoundException();
        }

        if ($personIdsWithViews->has($person)) {
            return $personIdsWithViews->get($person);
        }
    }

    /** @throws CacheBusinessLineNotFoundException */
    private function getByBusinessLine(string $line): ?int
    {
        $businesslines = $this->cacheBusinesslines(BusinessLine::KEY_CACHE);

        if (!$businesslines || $businesslines->isEmpty()) {
            throw new CacheBusinessLineNotFoundException();
        }

        if ($businesslines->has($line)) {
            return $businesslines->get($line);
        }

        return null;
    }

    /** @return string[] */
    private function getViewsAndSubViewsByPerson(int $personId): array
    {
        $viewsWithSubViews = $this->getViewsWithSubViewsByPersonId($personId);

        if (!empty($viewsWithSubViews)) {
            return [
                data_get($viewsWithSubViews, 'view_id'),
                data_get($viewsWithSubViews, 'sub_view_id'),
            ];
        }
    }

    /** @return string[] */
    private function mountTeam(
        int $personId,
        int $personIdSalesManager,
        int $personIdViewManager,
        int $place,
        int $businessline
    ): array {

        [$viewId, $subViewId] = $this->getViewsAndSubViewsByPerson($personId);

        return [
            'people_id'         => $personId,
            'manager_id'        => $personIdSalesManager,
            'view_manager_id'   => $personIdViewManager,
            'place_id'          => $place,
            'business_line_id'  => $businessline,
            'view_id'           => $viewId,
            'sub_view_id'       => $subViewId,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ];
    }
}
