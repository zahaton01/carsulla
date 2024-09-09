<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Entity\CurrencyExchangeRate;
use App\Domain\Repository\CurrencyExchangeRateRepositoryInterface;
use App\Infrastructure\Service\CurrencyExchangeRate\Service\CurrencyConverter;
use PHPUnit\Framework\TestCase;

class CurrencyConverterUnitTest extends TestCase
{
    public function testSuccessDirect()
    {
        $rateRepositoryMock = $this->createMock(CurrencyExchangeRateRepositoryInterface::class);
        $rateRepositoryMock
            ->expects($this->once())
            ->method('findLatestByCurrencyPair')
            ->with($this->equalTo('USD'), $this->equalTo('GBP'))
            ->willReturn(new CurrencyExchangeRate('USD', 'GBP', 0.9));

        $converter = new CurrencyConverter($rateRepositoryMock);

        $this->assertSame(
            90.0,
            $converter->convert('USD', 'GBP', 100)
        );
    }

    public function testSuccessDoubleConversion()
    {
        $rateRepositoryMock = $this->createMock(CurrencyExchangeRateRepositoryInterface::class);

        $rateMap = [
            ['USD', 'GBP', null],
            ['USD', 'RUB', new CurrencyExchangeRate('USD', 'RUB', 0.9)],
            ['RUB', 'GBP', new CurrencyExchangeRate('RUB', 'GBP', 0.9)],
        ];
        $rateRepositoryMock
            ->method('findLatestByCurrencyPair')
            ->willReturnMap($rateMap);

        $converter = new CurrencyConverter($rateRepositoryMock);

        $this->assertSame(
            100 * 0.9 * 0.9,
            $converter->convert('USD', 'GBP', 100)
        );
    }
}
