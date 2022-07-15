<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Team;
use App\Models\User;
use App\Models\View;
use App\Models\Place;
use App\Models\BusinessLine;
use App\Imports\BaseImportable;
use Illuminate\Support\Collection;
use App\Domain\Services\TeamService;
use App\Domain\Components\Facades\LoggerFacade;
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
            if (!empty($validatedRows)) {
                $this->processTeam(collect($validatedRows));
                $this->generateCacheRowsSuccessfully($validatedRows);
            }
        } catch (Throwable $th) {
            LoggerFacade::error(
                Team::GROUP_LOGGER,
                'Exception Clients',
                [
                    'messagem'  => $th->getMessage(),
                    'exception' => $th,
                ]
            );
        }
    }

    private function processTeam(Collection $team): void
    {
        foreach ($team as $value) {
            try {
                $teams = $this->mountTeam($value);

                $this->teamService->updateOrCreate($teams);

                LoggerFacade::info(
                    Team::GROUP_LOGGER,
                    'Importacao Carteira com sucesso',
                    [
                        'Carteira' => $teams
                    ]
                );
            } catch (PlaceNotFoundException $ex) {
                LoggerFacade::error(
                    Team::GROUP_LOGGER,
                    'PDV (place) nao encontrado em importacao de carteira',
                    [
                        'message'   => $ex->getMessage(),
                        'erro'      => $ex,
                        'carteira'  => $teams
                    ]
                );
                continue;
            } catch (UserNotFoundException $ex) {
                LoggerFacade::error(
                    Team::GROUP_LOGGER,
                    'Usuario nao encontrado em importacao de carteira',
                    [
                        'message'   => $ex->getMessage(),
                        'erro'      => $ex,
                        'carteira'  => $teams
                    ]
                );
                continue;
            } catch (Exception $ex) {
                LoggerFacade::error(
                    Team::GROUP_LOGGER,
                    'Erro Processar Carteira.',
                    [
                        'message'   => $ex->getMessage(),
                        'erro'      => $ex,
                        'carteira'  => $teams
                    ]
                );
            }
        }
    }


    private function mountTeam(array $team): array
    {
        list(
            $personId,
            $personIdSalesManager,
            $personIdViewManager,
            $place,
            $businessline
        ) = $this->destructuringTeam($team);

        list($viewId, $subViewId) = $this->getViewsAndSubViewsByPerson($personId);

        return [
            'people_id'         => $personId,
            'manager_id'        => $personIdSalesManager,
            'view_manager_id'   => $personIdViewManager,
            'place_id'          => $place,
            'business_line_id'  => $businessline,
            'view_id'           => $viewId,
            'sub_view_id'       => $subViewId
        ];
    }

    /**
     * @param string[]
     * @return int[]
     */
    private function destructuringTeam(array $team): array
    {
        return [
            $this->getUserByRegistration(
                data_get($team, 'registration')
            ),
            $this->getUserByRegistration(
                data_get($team, 'registration_sales_manager')
            ),
            $this->getUserByRegistration(
                data_get($team, 'registration_view_manager')
            ),
            $this->getPlaceByCnpj(
                data_get($team, 'cnpj')
            ),
            $this->getByBusinessLine(
                data_get($team, 'line')
            ),
        ];
    }

    /** @throws CacheUsersNotFoundException */
    private function getUserByRegistration(string $registration): ?int
    {
        $userIds = $this->cacheUserIds(User::KEY_CACHE);

        if (!$userIds) {
            throw new CacheUsersNotFoundException();
        }

        if ($userIds->has($registration)) {
            return $userIds->get($registration);
        }

        return null;
    }

    /** @throws CachePlaceNotFoundException */
    private function getPlaceByCnpj(string $cnpj): ?int
    {
        $cnpjIds = $this->cacheCnpj(Place::KEY_CACHE);

        if (!$cnpjIds || $cnpjIds->isEmpty()) {
            throw new CachePlaceNotFoundException();
        }

        if ($cnpjIds->has($cnpj)) {
            return $cnpjIds->get($cnpj);
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
}
