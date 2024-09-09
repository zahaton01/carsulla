<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\ECB\DTO\CurrencyExchangeRate;

use Symfony\Component\Serializer\Attribute\SerializedName;

class ExchangeRatesEnvelope
{
    /**
     * @var ExchangeRatesCube[]
     */
    #[SerializedName('Cube')]
    public array $timedCubes = [];
}
