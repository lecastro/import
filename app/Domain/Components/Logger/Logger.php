<?php

declare(strict_types=1);

namespace App\Domain\Components\Logger;

use Illuminate\Support\Facades\Log;
use App\Domain\Components\Logger\Contract\NoneLoggerContract;

class Logger implements NoneLoggerContract
{
    public function error(string $type, string $name, array $context): void
    {
        Log::error($type, $this->formatLog(
            $name,
            $context
        ));
    }

    public function warning(string $type, string $name, array $context): void
    {
        Log::warning($type, $this->formatLog(
            $name,
            $context
        ));
    }

    public function alert(string $type, string $name, array $context): void
    {
        Log::alert($type, $this->formatLog(
            $name,
            $context
        ));
    }

    public function info(string $type, string $name, array $context): void
    {
        Log::info($type, $this->formatLog(
            $name,
            $context
        ));
    }

    /** @return string[] */
    private function formatLog(string $name, array $context): array
    {
        return [$name => $context];
    }
}
