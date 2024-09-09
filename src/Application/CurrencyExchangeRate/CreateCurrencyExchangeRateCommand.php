<?php

declare(strict_types=1);

namespace App\Application\CurrencyExchangeRate;

class CreateCurrencyExchangeRateCommand
{
    public function __construct(
        public string $from,
        public string $to,
        public float $rate
    ) {
    }
}
