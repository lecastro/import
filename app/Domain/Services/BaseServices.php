<?php

declare(strict_types=1);

namespace app\Domain\Services;

use Illuminate\Support\LazyCollection;

abstract class BaseServices
{
    /** @var int */
    protected const CHUCK_SIZE = 1;

    /** @var int */
    protected const SKIP_FILE = 1;

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
}
