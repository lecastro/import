<?php

declare(strict_types=1);

namespace App\Domain\Components\Adapters;

use Illuminate\Queue\Events\JobProcessed;

class JobProcessedPayloadAdapter
{
    /** @var JobProcessed */
    protected $jobProcessed;

    public function __construct(JobProcessed $jobProcessed)
    {
        $this->jobProcessed = $jobProcessed->job;
    }

    /** @return mixed */
    private function payloadDecode(): object
    {
        return json_decode(
            $this->jobProcessed->getRawBody()
        );
    }

    /** @return mixed */
    private function payloadUnserialize(): object
    {
        return unserialize(
            $this->payloadDecode()->data->command
        );
    }

    /** @return array[] */
    private function payloadJobToArray(): array
    {
        return collect(
            $this->payloadUnserialize()
        )->toArray();
    }

    /**  @return mixed[]  */
    public function jobPayload(): array
    {
        foreach ($this->payloadJobToArray() as $value) {
            return [
                data_get($value[0], 'uploadHistoryModel'),
                data_get($value[0], 'errors'),
                data_get($value[0], 'succefully')
            ];
        }
    }
}
