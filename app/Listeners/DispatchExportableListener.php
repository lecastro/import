<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Traits\CacheRedis;
use App\Events\DispatchExportableEvent;
use Illuminate\Queue\Events\JobProcessed;
use App\Domain\Services\ImportTeamService;
use App\Domain\Components\Adapters\JobProcessedPayloadAdapter;

class DispatchExportableListener
{
    use CacheRedis;

    /** @var ImportTeamService */
    protected $importTeamService;

    public function __construct(ImportTeamService $importTeamService)
    {
        $this->importTeamService = $importTeamService;
    }

    public function handle(DispatchExportableEvent $event): void
    {
        try {
            list($uploadHistory, $errors, $succefully)
                = $this->jobProcessedPayloadAdapter($event->getJobProcessed());

            $errorsCache     = $this->returnArray($this->getCache($errors));
            $succefullyCache = $this->returnArray($this->getCache($succefully));

            $this->importTeamService->errorsValidationExportable($errorsCache, $uploadHistory);

            $this->importTeamService->getLinesErrorsAndValidTeams(
                $errorsCache,
                $succefullyCache,
                $uploadHistory
            );

            $this->clearCache($errors, $succefully);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**  @return mixed[]  */
    protected function jobProcessedPayloadAdapter(JobProcessed $event)
    {
        return (new JobProcessedPayloadAdapter($event))->jobPayload();
    }

    /** @return string[] */
    private function returnArray(?array $data): array
    {
        return (is_array($data)) ? $data : [];
    }

    private function clearCache(string $errors, string $succefully): void
    {
        $this->forget($errors);
        $this->forget($succefully);
    }
}
