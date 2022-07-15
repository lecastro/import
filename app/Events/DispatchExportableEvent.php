<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class DispatchExportableEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var JobProcessed */
    protected $jobProcessed;

    public function __construct(JobProcessed $jobProcessed)
    {
        $this->jobProcessed = $jobProcessed;
    }

    public function getJobProcessed(): JobProcessed
    {
        return $this->jobProcessed;
    }
}
