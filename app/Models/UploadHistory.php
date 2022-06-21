<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UploadHistory extends Model
{
    use SoftDeletes;

    /** @var string */
    public const STATUS_UPLOAD_IN_PROGRESS   = 'EM ANDAMENTO';

    /** @var string */
    public const STATUS_UPLOAD_PROCESSED     = 'PROCESSADO';

    /** @var string */
    public const STATUS_UPLOAD_IMPORT_FAILED = 'FALHA NA IMPORTAÇÃO';

    /** @var string */
    protected $connection = 'mysqlSchedule';

    /** @var string */
    protected $table = 'upload_histories';

    /** @var string[] */
    protected $fillable = [
        'id',
        'person_id',
        'title',
        'path_temporary',
        'path_file',
        'path_file_logs',
        'module',
        'status',
        'type',
        'error_lines',
        'successful_lines'
    ];
}
