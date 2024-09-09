<?php

namespace App\Domain\Repository;

use App\Domain\Entity\CurrencyExchangeRate;

interface CurrencyExchangeRateRepositoryInterface
{
    public function save(CurrencyExchangeRate $currencyExchangeRate): void;
    public function findLatestByCurrencyPair(string $from, string $to): ?CurrencyExchangeRate;
}
