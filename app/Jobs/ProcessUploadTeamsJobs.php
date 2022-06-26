<?php

declare(strict_types=1);

namespace App\Jobs;

use Throwable;
use App\Models\Team;
use App\Models\UploadHistory;
use App\Domain\Services\ImportTeamService;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Domain\Components\Facades\LoggerFacade;

class ProcessUploadTeamsJobs extends ProcessBaseJobs implements ShouldQueue
{
    protected UploadHistory $upload;

    public function __construct(UploadHistory $upload)
    {
        $this->upload = $upload;
    }

    public function handle(): void
    {
        $importable = resolve(ImportTeamService::class);

        $importable->team(
            $this->upload
        );

        $this->process();

        unlink($this->upload->path_temporary);
    }

    public function failed(Throwable $exception): void
    {
        $this->processFail();

        LoggerFacade::error(
            Team::GROUP_LOGGER,
            'Falha ao processar Jobs de importação Teams (Clientes).',
            [
                'message'   => $exception->getMessage(),
                'exception' => $exception
            ]
        );
    }
}
