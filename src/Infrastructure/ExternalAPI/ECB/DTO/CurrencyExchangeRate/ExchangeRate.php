<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\ECB\DTO\CurrencyExchangeRate;

use Symfony\Component\Serializer\Attribute\SerializedName;

class ExchangeRate
{
    #[SerializedName('@currency')]
    public string $currency = '';

    #[SerializedName('@rate')]
    public float $rate = 0.0;
}
