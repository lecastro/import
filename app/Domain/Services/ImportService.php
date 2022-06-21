<?php

declare(strict_types=1);

namespace app\Domain\Services;

use Illuminate\Http\Request;
use App\Models\UploadHistory;
use App\Imports\TeamImportable;
use Maatwebsite\Excel\Importer;
use App\Domain\Services\DeletedRecordHistoryService;

class ImportService
{
    /** @var string */
    private const DISK = 's3';

    /** @var string */
    private const EXTENSION = 'Csv';

    protected Importer $service;

    protected TeamImportable $teamImportable;

    protected DeletedRecordHistoryService $deletedRecordHistoryService;

    public function __construct(
        Importer $importer,
        TeamImportable $teamImportable,
        DeletedRecordHistoryService $deletedRecordHistoryService
    ) {
        $this->service                      = $importer;
        $this->teamImportable               = $teamImportable;
        $this->deletedRecordHistoryService  = $deletedRecordHistoryService;
    }

    public function team(Request $request, UploadHistory $uploadHistory): void
    {
        $this->service->import(
            $this->teamImportable,
            $request->file('filename'),
            null,
            self::EXTENSION
        );

        if ($this->teamImportable->getValidationErrors()) {
            $path = $this->mountPath();

            Excel::store(
                new ErrorsValidationExportable($this->teamImportable->getValidationErrors()),
                $path,
                self::DISK
            );

            $this->generatePathLog(
                $uploadHistory,
                $path
            );
        }

        $this->getLinesErrorsAndValid(
            $this->teamImportable->getValidationErrors(),
            $this->teamImportable->getSuceessFulLines(),
            $uploadHistory
        );

        $this->dispatchDeletionOfTeams();
    }

    public function dispatchDeletionOfTeams(): void
    {
        ProcessDeleteAllTeamsJobs::dispatch(
            $this->teamImportable,
            $this->deletedRecordHistoryService
        )
            //->onConnection('database')
            ->onQueue(
                NameQueue::PROCESS_DELETE_TEAMS_THAT_WHERE_NOT_CREATED_MANUALLY
            );
    }
}
