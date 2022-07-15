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

    public function formatToUpper(string $value): string
    {
        return mb_strtoupper($value);
    }

    public function formatValueToBRCoin(float $value): string
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
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

    public static function explode(string $delemiter, string $value): array
    {
        return explode($delemiter, $value);
    }

    public static function hash(): string
    {
        return uniqid(date('HisYmd'));
    }

    public static function registrationAdapter(string $registration): string
    {
        return trim(str_replace('F', '', $registration));
    }

    public static function cnpjAdapter(string $cpf): string
    {
        return trim(preg_replace('/[^0-9]/', '', $cpf));
    }
}
