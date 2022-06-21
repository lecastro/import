<?php

declare(strict_types=1);

namespace app\Domain\Services\Contract;

use Illuminate\Http\UploadedFile;

/**
 * Responsible for receiving a saved document on AWS and returning a path to that saved document.
 */
interface GeneratingDocumentContract
{
    /**
     * set document content to file.
     */
    public function setDocument(UploadedFile $document): GeneratingDocumentContract;

    /**
     * create a new path mount a name hash saved on AWS and generate a path.
     */
    public function generate(string $disk): string;

    /**
     * returns an AWS document path.
     */
    public function getPath(): string;
}
