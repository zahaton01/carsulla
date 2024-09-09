<?php

namespace App\Infrastructure\Service\CurrencyExchangeRate\DataProvider;

use App\Infrastructure\Service\CurrencyExchangeRate\DTO\CurrencyRateDTO;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(self::TAG)]
interface DataProviderInterface
{
    public const string TAG = 'currency_exchange_rate_data_provider';

    public function supports(): bool;

    /**
     * @return CurrencyRateDTO[]
     */
    public function fetchData(): iterable;
}
