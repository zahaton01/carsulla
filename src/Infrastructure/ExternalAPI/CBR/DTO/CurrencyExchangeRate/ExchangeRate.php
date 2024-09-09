<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\CBR\DTO\CurrencyExchangeRate;

use Symfony\Component\Serializer\Attribute\SerializedName;

class ExchangeRate
{
    #[SerializedName('CharCode')]
    public string $currency = '';

    #[SerializedName('Value')]
    public string $rate = '';
}
