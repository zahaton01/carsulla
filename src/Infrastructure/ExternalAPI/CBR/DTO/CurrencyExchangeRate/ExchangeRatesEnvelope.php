<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\CBR\DTO\CurrencyExchangeRate;

use App\Infrastructure\ExternalAPI\ECB\DTO\CurrencyExchangeRate\ExchangeRatesCube;
use Symfony\Component\Serializer\Attribute\SerializedName;

class ExchangeRatesEnvelope
{
    /**
     * @var ExchangeRate[]
     */
    #[SerializedName('Valute')]
    public array $rateCollection = [];
}
