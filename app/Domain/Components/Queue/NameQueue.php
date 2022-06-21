<?php

declare(strict_types=1);

namespace App\Domain\Components\Queue;

class NameQueue
{
    /** @var string */
    public const PROCESS_IMPORT_BUDGET = 'IMPORT_BUDGET';

    /** @var string */
    public const PROCESS_IMPORT_CLIENTS = 'IMPORT_CLIENTS';

    /** @var string */
    public const PROCESS_IMPORT_TEAMS = 'IMPORT_TEAMS';

    /** @var string */
    public const PROCESS_IMPORT_USERS = 'IMPORT_USERS';

    /** @var string */
    public const PROCESS_IMPORT_EMAIL_ATA = 'IMPORT_EMAIL_ATA';

    /** @var string */
    public const PROCESS_DELETE_TEAMS_THAT_WHERE_NOT_CREATED_MANUALLY
    = 'PROCESS_DELETE_TEAMS';
}
