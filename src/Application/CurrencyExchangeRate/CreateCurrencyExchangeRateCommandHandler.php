<?php

declare(strict_types=1);

namespace App\Application\CurrencyExchangeRate;

use App\Domain\Entity\CurrencyExchangeRate;
use App\Domain\Repository\CurrencyExchangeRateRepositoryInterface;
use App\Infrastructure\Messenger\CommandBus\CommandHandlerInterface;

readonly class CreateCurrencyExchangeRateCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CurrencyExchangeRateRepositoryInterface $currencyExchangeRateRepository
    ) {
    }

    public function __invoke(CreateCurrencyExchangeRateCommand $command): void
    {
        $rate = new CurrencyExchangeRate(
            $command->from,
            $command->to,
            $command->rate
        );

        $this->currencyExchangeRateRepository->save($rate);
    }
}
