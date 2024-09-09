<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\CurrencyExchangeRate\DataProvider;

use App\Infrastructure\ExternalAPI\ECB\DTO\CurrencyExchangeRate\ExchangeRatesEnvelope;
use App\Infrastructure\ExternalAPI\ECB\ECBClientInterface;
use App\Infrastructure\Service\CurrencyExchangeRate\DTO\CurrencyRateDTO;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class ECBDataProvider implements DataProviderInterface
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
        private ECBClientInterface $ECBClient,
        private SerializerInterface $serializer
    ) {

    }

    public function supports(): bool
    {
        return $this->parameterBag->get('currency_exchange_rate')['data_providers']['ecb'] === 'enabled';
    }

    public function fetchData(): iterable
    {
        $data = $this->serializer->deserialize($this->getXml(), ExchangeRatesEnvelope::class, 'xml');

        foreach ($data->timedCubes as $timedCube) {
            foreach ($timedCube->currencyRates as $currencyRate) {
                yield new CurrencyRateDTO(
                    'EUR',
                    $currencyRate->currency,
                    $currencyRate->rate
                );
            }
        }
    }

    private function getXml(): string
    {
        $rawXml = $this->ECBClient->sendGet('/stats/eurofxref/eurofxref-daily.xml')->getBody()->getContents();
        $pattern = '/<Cube\b[^>]*>(?:[\s\S]*?<Cube\b[^>]*>[\s\S]*?<\/Cube>[\s\S]*?)+<\/Cube>/';
        preg_match($pattern, $rawXml, $matches);

        return $matches[0];
    }
}
