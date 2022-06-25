<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Models\UploadHistory;
use App\Imports\TeamImportable;
use App\Domain\Services\BaseServices;
use Illuminate\Support\LazyCollection;
use App\Domain\Components\Queue\NameQueue;
use App\Domain\Components\Helpers\DateHelper;
use App\Domain\Components\Helpers\StringHelper;
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
                $this->teamImportable->importProcess($team);
            });

        $this->dispatchDeletionOfTeams();
    }

    /** @return array[] */
    private function processFormatLinesFile(LazyCollection $lines): array
    {
        $data = [];

        foreach ($lines as $line) {
            $line = StringHelper::explode(';', $line[0]);
            $data[] = [
                'cnpj'                          => $line[0] ?? '',
                'matricula'                     => $line[1] ?? '',
                'linha'                         => $line[2] ?? '',
                'matricula_gerente_de_vendas'   => $line[3] ?? '',
                'matricula_gerente_de_regional' => $line[4] ?? '',
                'created_at'                    => DateHelper::dateNow(),
                'updated_at'                    => DateHelper::dateNow()
            ];
        }

        return $data;
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

    public function dispatchProcessImportFileInChunk($team): void
    {
        ProcessImportFileInChunkForQueueJobs::dispatch(
            $team,
            $this->teamImportable
        )->onQueue(
            NameQueue::PROCESS_IMPORT_FILE_IN_CHUNCK
        );
    }
}
