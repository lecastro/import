<?php

declare(strict_types=1);

namespace App\Jobs;

use Throwable;
use App\Models\Team;
use Illuminate\Bus\Queueable;
use App\Imports\TeamImportable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain\Components\Facades\LoggerFacade;

class ProcessImportFileInChunkForQueueJobs implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected array $team;

    protected TeamImportable $teamImportable;

    public function __construct(array $team, TeamImportable $teamImportable)
    {
        $this->team             = $team;
        $this->teamImportable   = $teamImportable;
    }

    public function handle(): void
    {
        $this->teamImportable->importProcessInRow(
            $this->team
        );
    }

    public function failed(Throwable $exception): void
    {
        LoggerFacade::error(
            Team::GROUP_LOGGER,
            'Falha ao processar Arquivo teams em linha',
            [
                'mensagem'  => $exception->getMessage(),
                'erro'      => $exception
            ]
        );
    }
}
