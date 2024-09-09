<?php

namespace App\Infrastructure\Service\CurrencyExchangeRate\Service;

use App\Domain\Entity\CurrencyExchangeRate;
use App\Domain\Repository\CurrencyExchangeRateRepositoryInterface;
use LogicException;

readonly class CurrencyConverter implements CurrencyConverterInterface
{
    public function __construct(
        private CurrencyExchangeRateRepositoryInterface $currencyExchangeRateRepository
    ) {
    }

    public function convert(string $from, string $to, float $amount, ?string $doubleConversionFallbackCurrency = 'RUB'): float
    {
        $rate = $this->currencyExchangeRateRepository->findLatestByCurrencyPair($from, $to);
        if ($rate) {
            return $this->convertDirectly($rate, $amount);
        }

        $rateFromToFallback = $this->currencyExchangeRateRepository->findLatestByCurrencyPair($from, $doubleConversionFallbackCurrency);
        $rateFallbackToTo = $this->currencyExchangeRateRepository->findLatestByCurrencyPair($doubleConversionFallbackCurrency, $to);

        if ($rateFromToFallback && $rateFallbackToTo) {
            return $this->convertWithDoubleConversion($rateFromToFallback, $rateFallbackToTo, $amount);
        }

        throw new LogicException('Rate was not found for either direct or double conversion.');
    }

    private function convertDirectly(CurrencyExchangeRate $rate, float $amount): float
    {
        return $amount * $rate->getRate();
    }

    private function convertWithDoubleConversion(CurrencyExchangeRate $rateFromToFallback, CurrencyExchangeRate $rateFallbackToTo, float $amount): float
    {
        $amountInFallback = $amount * $rateFromToFallback->getRate();
        return $amountInFallback * $rateFallbackToTo->getRate();
    }
}
