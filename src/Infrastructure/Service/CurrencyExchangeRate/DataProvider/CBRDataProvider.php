<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\CurrencyExchangeRate\DataProvider;

use App\Infrastructure\ExternalAPI\CBR\CBRClientInterface;
use App\Infrastructure\ExternalAPI\CBR\DTO\CurrencyExchangeRate\ExchangeRatesEnvelope;
use App\Infrastructure\Service\CurrencyExchangeRate\DTO\CurrencyRateDTO;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class CBRDataProvider implements DataProviderInterface
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
        private CBRClientInterface $CBRClient,
        private SerializerInterface $serializer
    ) {

    }

    public function supports(): bool
    {
        return $this->parameterBag->get('currency_exchange_rate')['data_providers']['cbr'] === 'enabled';
    }

    public function fetchData(): iterable
    {
        $rawXml = $this->CBRClient->sendGet('/scripts/XML_daily.asp')->getBody()->getContents();
        $data = $this->serializer->deserialize($rawXml, ExchangeRatesEnvelope::class, 'xml');

        foreach ($data->rateCollection as $rate) {
            yield new CurrencyRateDTO(
                $rate->currency,
                'RUB',
                (float)str_replace(',', '.', $rate->rate)
            );
        }
    }
}
