<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\CurrencyExchangeRate;

use App\Infrastructure\Service\CurrencyExchangeRate\DataProvider\DataProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class CurrencyExchangeRateExternalSourceProvider implements CurrencyExchangeRateExternalSourceProviderInterface
{
    public function __construct(
        /**
         * @var DataProviderInterface[]
         */
        #[AutowireIterator(DataProviderInterface::TAG)]
        private iterable $dataProviders
    ) {
    }

    public function provide(): iterable
    {
        foreach ($this->dataProviders as $dataProvider) {
            if ($dataProvider->supports()) {
                foreach ($dataProvider->fetchData() as $rate) {
                    yield $rate;
                }
            }
        }
    }
}
