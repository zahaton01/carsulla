<?php

namespace App\Infrastructure\Service\CurrencyExchangeRate\Service;

interface CurrencyConverterInterface
{
    public function convert(
        string $from,
        string $to,
        float $amount,
        ?string $doubleConversionFallbackCurrency = 'RUB'
    ): float;
}
