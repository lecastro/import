<?php

declare(strict_types=1);

namespace app\Domain\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\UploadHistory;
use Illuminate\Http\UploadedFile;
use App\Jobs\ProcessUploadTeamsJobs;
use App\Domain\Services\BaseServices;
use App\Domain\Components\Queue\NameQueue;
use App\Http\Requests\Uploads\UploadFileRequest;
use App\Domain\Services\GenerateDocumentAwsService;
use App\Domain\Repositories\Eloquent\UploadHistoryRepository;

class UploadService extends BaseServices
{
    /** @var string */
    protected const MODULE = 'schedule';

    /** @var string*/
    private const DISK = 's3';

    /** @var string*/
    private const DISK_LOCAL = 'local';

    protected GenerateDocumentAwsService $generateDocument;

    protected UploadHistoryRepository $uploadHistoryRepository;

    public function __construct(
        GenerateDocumentAwsService $generateDocument,
        UploadHistoryRepository $uploadHistoryRepository
    ) {
        $this->generateDocument         = $generateDocument;
        $this->uploadHistoryRepository  = $uploadHistoryRepository;
    }

    public function uploadTeams(Request $request): void
    {
        $file     = $request->file('filename');

        $pathTemp = $this->generateDocument($file, self::DISK_LOCAL);
        //$pathS3   = $this->generateDocument($file, self::DISK);
        $pathS3   = $pathTemp;

        $upload = $this->mountUpload(
            $this->formatFileName('Carteiras'),
            self::MODULE,
            $pathTemp,
            $pathS3,
            $this->formatFileName('Carteiras', false)
        );

        $payload = $this->uploadHistoryRepository->create($upload);

        ProcessUploadTeamsJobs::dispatch(
            $payload
        )
            ->onQueue(
                NameQueue::PROCESS_IMPORT_TEAMS
            );
    }

    protected function generateDocument(UploadedFile $document, string $disk): string
    {
        $this->validatorCsv($document);

        $this->generateDocument
            ->setDocument($document)
            ->generate($disk);

        return (string) $this->generateDocument->getPath();
    }

    protected function validatorCsv(UploadedFile $document): UploadedFile
    {
        if (
            empty($document) ||
            $document->isValid() === false ||
            $document->getClientOriginalExtension() !== 'csv'
        ) {
            throw new CsvNotFoundException();
        }

        return $document;
    }

    protected function storagePath(string $path): string
    {
        return storage_path("app/{$path}");
    }

    public function formatFileName(string $fileName, $isValid = true): string
    {
        if ($isValid) {
            return $fileName . ' - ' . Carbon::now()->format('d/m/Y');
        }

        return $fileName;
    }

    /** @return string[] */
    protected function mountUpload(
        string $title,
        string $module,
        string $pathTemp,
        string $pathS3,
        $type
    ): array {
        return [
            'person_id'      => 1,
            'title'          => $title,
            'path_temporary' => $this->storagePath($pathTemp),
            'path_file'      => $pathS3,
            'path_file_logs' => null,
            'module'         => $module,
            'status'         => UploadHistory::STATUS_UPLOAD_IN_PROGRESS,
            'type'           => $type
        ];
    }
}
