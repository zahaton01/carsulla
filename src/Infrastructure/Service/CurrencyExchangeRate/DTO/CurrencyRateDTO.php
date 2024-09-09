<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\CurrencyExchangeRate\DTO;

class CurrencyRateDTO
{
    public function __construct(
        public string $from,
        public string $to,
        public float $rate
    ) {
    }
}
