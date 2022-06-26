<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Models\Team;
use App\Models\UploadHistory;
use App\Imports\TeamImportable;
use App\Domain\Services\BaseServices;
use Illuminate\Support\LazyCollection;
use App\Domain\Components\Queue\NameQueue;
use App\Domain\Components\Facades\LoggerFacade;
use App\Domain\Components\Output\TeamFileOutPut;
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
            ->map(function (LazyCollection $team): array {
                return $this->processFormatLinesFile($team);
            })
            ->each(function (array $team): void {
                if (!empty($team)) {
                    $this->dispatchProcessImportFileInChunk(
                        $team
                    );
                    LoggerFacade::info(
                        Team::GROUP_LOGGER,
                        'Processando arquivo de  importação',
                        [
                            'carteira' => $team
                        ]
                    );
                }
            });

        //$this->dispatchDeletionOfTeams();
    }

    /** @return array[] */
    private function processFormatLinesFile(LazyCollection $lines): array
    {
        return (new TeamFileOutPut($lines))->processFileInRows();
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

    public function dispatchProcessImportFileInChunk(array $team): void
    {
        ProcessImportFileInChunkForQueueJobs::dispatch(
            $team,
            $this->teamImportable
        )->onQueue(
            NameQueue::PROCESS_IMPORT_FILE_IN_CHUNCK
        );
    }
}
