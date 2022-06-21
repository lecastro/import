<?php

declare(strict_types=1);

namespace app\Domain\Services;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Domain\Services\Contract\GeneratingDocumentContract;

class GenerateDocumentAwsService implements GeneratingDocumentContract
{
    /** @var string */
    const ENV_BASE_PATH = 'DOCUMENT_BASE_PATH';

    /** @var string */
    const DISK = 'local';

    /** @var file */
    protected $documentContent;

    /** @var string */
    protected $path;

    /** @var string */
    protected $fileName;

    public function setDocument(UploadedFile $document): GeneratingDocumentContract
    {
        $this->documentContent = $document;

        return $this;
    }

    public function generate(string $disk = self::DISK): string
    {
        $this->mountPath();

        $this->mountName();

        $this->saveInStorage($disk);

        return $this->getPath();
    }

    public function getPath(): string
    {
        return $this->path() . $this->fileName;
    }

    protected function mountPath(): void
    {
        $year = Carbon::now()->format('Y');

        $this->path = "public/application/schedule/uploads/{$year}/";
    }

    protected function mountName(): void
    {
        $this->fileName = "{$this->documentContent->hashName()}";
    }

    protected function saveInStorage(string $disk): void
    {
        Storage::disk($disk)->putFileAs($this->path(), $this->documentContent, $this->fileName);
    }

    protected function path(): string
    {
        return $this->getEnvBasePath() . $this->path;
    }

    protected function getEnvBasePath(): ?string
    {
        $env = env(self::ENV_BASE_PATH);

        if ($env === '/') {
            return null;
        }

        return $env;
    }
}
