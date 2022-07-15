<?php

declare(strict_types=1);

namespace App\Domain\Components\Queue;

class NameQueue
{
    /** @var string */
    public const PROCESS_IMPORT_TEAMS = 'IMPORT_TEAMS';

    /** @var string */
    public const PROCESS_START_CACHE = 'START_CACHE';

    /** @var string */
    public const PROCESS_IMPORT_FILE_IN_CHUNCK = 'PROCESS_IMPORT_FILE_IN_CHUNCK';
}
