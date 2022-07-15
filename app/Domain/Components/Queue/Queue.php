<?php

declare(strict_types=1);

namespace App\Domain\Components\Queue;

use Event;
use App\Events\DispatchExportableEvent;
use Illuminate\Queue\Events\JobProcessed;
use App\Domain\Components\Queue\NameQueue;
use Illuminate\Support\Facades\Queue as QueueFacede;

class Queue
{
    public function dispatch(): void
    {
        QueueFacede::after(
            function (JobProcessed $event): void {
                if ($this->isTypeJob($event)) {
                    if ($this->size() === 0) {
                        Event::dispatch(
                            new DispatchExportableEvent($event)
                        );
                    }
                }
            }
        );
    }

    private function isTypeJob($event): bool
    {
        return $event->job->resolveName() === 'App\Jobs\ProcessImportFileInChunkForQueueJobs';
    }

    private function size(): int
    {
        return QueueFacede::size(NameQueue::PROCESS_IMPORT_FILE_IN_CHUNCK);
    }
}
