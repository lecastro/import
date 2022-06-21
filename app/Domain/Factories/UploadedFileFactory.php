<?php

declare(strict_types=1);

namespace App\Domain\Factories;

use Illuminate\Http\UploadedFile;

class UploadedFileFactory
{
    public function make(string $path, string $file): UploadedFile
    {
        return new UploadedFile($path, $file);
    }
}
