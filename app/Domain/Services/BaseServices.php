<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Models\UploadHistory;
use Illuminate\Support\LazyCollection;
use App\Exports\ErrorsValidationExportable;
use App\Domain\Components\Helpers\DateHelper;
use App\Domain\Components\Helpers\StringHelper;
use Maatwebsite\Excel\Facades\Excel;

abstract class BaseServices
{
    /** @var int */
    protected const CHUCK_SIZE = 1;

    /** @var int */
    protected const SKIP_FILE = 1;

    /** @var string */
    protected const MODULE = 'schedule';

    /** @var string*/
    protected const DISK = 's3';

    /** @var string*/
    protected const DISK_LOCAL = 'local';

    protected function lazyCollectionReadFile(string $path): LazyCollection
    {
        return
            LazyCollection::make(
                function () use ($path) {
                    $handle = fopen($path, 'r');
                    while ($line = fgetcsv($handle)) {
                        yield $line;
                    }
                }
            );
    }

    protected function mountPath(): string
    {
        $year = DateHelper::year();

        $hash = StringHelper::hash();

        return "/application/schedule/uploads/{$year}/{$hash}.csv";
    }

    protected function generatePathLog(UploadHistory $uploadHistory, string $pathLog): void
    {
        $uploadHistory->update([
            'path_file_logs' => $pathLog
        ]);
    }

    /**
     * @param array $error
     * @param array $valid
     * @param UploadHistory $uploadHistory
     */
    public function getLinesErrorsAndValid(array $error, array $valid, UploadHistory $uploadHistory): void
    {
        $error  = count($error) ?? 0;
        $valid  = count($valid) ?? 0;

        $uploadHistory->update([
            'error_lines'      => $error,
            'successful_lines' => $valid
        ]);
    }

    /**
     * @param array $error
     * @param array $valid
     * @param UploadHistory $uploadHistory
     */
    public function getLinesErrorsAndValidTeams(array $error, array $valid, UploadHistory $uploadHistory): void
    {
        $error  = count($error) ?? 0;
        $valid  = count($valid) ?? 0;

        $uploadHistory->update([
            'error_lines'       => $error,
            'successful_lines'  => $valid,
            'status'            => UploadHistory::STATUS_UPLOAD_PROCESSED,
        ]);
    }

    public function errorsValidationExportable(array $errors, UploadHistory $uploadHistory): void
    {
        if (!empty($errors)) {

            $path = $this->mountPath();

            Excel::store(
                new ErrorsValidationExportable($errors),
                $path,
                self::DISK
            );

            $this->generatePathLog(
                $uploadHistory,
                $path
            );
        }
    }
}
