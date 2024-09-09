<?php

declare(strict_types=1);

namespace App\HTTP\Request;

class ConvertCurrencyRequest implements HTTPRequestInterface
{
    public string $from;
    public string $to;
    public float $amount;
}
