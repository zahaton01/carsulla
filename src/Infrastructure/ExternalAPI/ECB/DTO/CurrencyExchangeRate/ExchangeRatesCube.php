<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\ECB\DTO\CurrencyExchangeRate;

use Symfony\Component\Serializer\Attribute\SerializedName;

class ExchangeRatesCube
{
    #[SerializedName('@time')]
    public ?string $time = null;

    /**
     * @var ExchangeRate[]
     */
    #[SerializedName('Cube')]
    public array $currencyRates = [];
}
