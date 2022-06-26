<?php

declare(strict_types=1);

namespace App\Domain\Components\Helpers;

class StringHelper
{
    private const VALUES_WITH_ACCENTS
    = 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ';

    private const VALUES_WITHOUT_ACCENTS
    = 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY';

    public static function make(): self
    {
        return new static();
    }

    public static function removeAccentsAndToUppercase(string $value): string
    {
        return strtoupper(
            strtr(
                utf8_decode($value),
                utf8_decode(self::VALUES_WITH_ACCENTS),
                self::VALUES_WITHOUT_ACCENTS
            )
        );
    }

    public static function explode(
        string $delemiter,
        string $value
    ): array {
        return explode($delemiter, $value);
    }
}
