<?php

namespace App\Infrastructure\Service\CurrencyExchangeRate;

use App\Infrastructure\Service\CurrencyExchangeRate\DTO\CurrencyRateDTO;

interface CurrencyExchangeRateExternalSourceProviderInterface
{
    /**
     * @return CurrencyRateDTO[]
     */
    public function provide(): iterable;
}
