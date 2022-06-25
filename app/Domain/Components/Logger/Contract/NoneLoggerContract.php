<?php

declare(strict_types=1);

namespace App\Domain\Components\Logger\Contract;

interface NoneLoggerContract
{
    public function error(string $type, string $name, array $context): void;

    public function warning(string $type, string $name, array $context): void;

    public function alert(string $type, string $name, array $context): void;

    public function info(string $type, string $name, array $context): void;
}
