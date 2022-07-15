<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\UploadHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;

abstract class ProcessBaseJobs
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @var string */
    private const FILENAME = 'filename';

    protected function process(): void
    {
        $this->upload->update([
            'status' => UploadHistory::STATUS_UPLOAD_PROCESSED,
        ]);
    }

    protected function processFail(): void
    {
        $this->upload->update([
            'status' => UploadHistory::STATUS_UPLOAD_IMPORT_FAILED,
        ]);
    }

    public function destroyFileTemp(): void
    {
        if (is_null($this->upload->path_temporary) === false) {
            unlink($this->upload->path_temporary);
        }
    }
}
