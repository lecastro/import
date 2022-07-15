<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Models\Team;
use App\Models\UploadHistory;
use App\Imports\TeamImportable;
use App\Domain\Services\BaseServices;
use Illuminate\Support\LazyCollection;
use App\Jobs\ProcessDeleteAllTeamsJobs;
use App\Domain\Components\Queue\NameQueue;
use App\Domain\Components\Adapters\TeamAdapter;
use App\Domain\Components\Facades\LoggerFacade;
use App\Jobs\ProcessImportFileInChunkForQueueJobs;
use App\Domain\Services\DeletedRecordHistoryService;

class ImportTeamService extends BaseServices
{
    protected TeamImportable $teamImportable;

    protected DeletedRecordHistoryService $deletedRecordHistoryService;

    public function __construct(
        TeamImportable $teamImportable,
        DeletedRecordHistoryService $deletedRecordHistoryService
    ) {
        $this->teamImportable               = $teamImportable;
        $this->deletedRecordHistoryService  = $deletedRecordHistoryService;
    }

    public function team(UploadHistory $uploadHistory): void
    {
        $path = $uploadHistory->path_temporary;

        $this->lazyCollectionReadFile($path)
            ->skip(self::SKIP_FILE)
            ->chunk(self::CHUCK_SIZE)
            ->map(function (LazyCollection $team) use ($uploadHistory): array {
                return $this->processLinesFile($team, $uploadHistory);
            })
            ->each(function (array $team): void {
                if (!empty($team)) {
                    $this->dispatchProcessImportFileInChunk($team);
                }
            });

        //$this->dispatchDeletionOfTeams();

        LoggerFacade::info(
            Team::GROUP_LOGGER,
            'finalizando processo de importacao em linha',
            []
        );
    }

    /** @return array[] */
    private function processLinesFile(LazyCollection $lines, $uploadHistory): array
    {
        $data = [];

        foreach ($lines as $line) {
            $data[] = (new TeamAdapter($line, $uploadHistory))->team();
        }

        return $data;
    }

    /** @param array[] */
    private function dispatchProcessImportFileInChunk(array $team): void
    {
        ProcessImportFileInChunkForQueueJobs::dispatch(
            $team,
            $this->teamImportable
        )->onQueue(
            NameQueue::PROCESS_IMPORT_FILE_IN_CHUNCK
        );

        LoggerFacade::info(
            Team::GROUP_LOGGER,
            'inicializando processo de importacao em linha',
            [
                'despachando carteira fila' => $team
            ]
        );
    }

    public function dispatchDeletionOfTeams(): void
    {
        ProcessDeleteAllTeamsJobs::dispatch(
            $this->teamImportable,
            $this->deletedRecordHistoryService
        )
            ->onQueue(
                NameQueue::PROCESS_DELETE_TEAMS_THAT_WHERE_NOT_CREATED_MANUALLY
            );
    }
}
