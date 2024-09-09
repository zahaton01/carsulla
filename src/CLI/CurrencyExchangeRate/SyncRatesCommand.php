<?php

declare(strict_types=1);

namespace App\CLI\CurrencyExchangeRate;

use App\Application\CurrencyExchangeRate\CreateCurrencyExchangeRateCommand;
use App\CLI\AbstractLockedCommand;
use App\Infrastructure\Messenger\CommandBus\CommandBusInterface;
use App\Infrastructure\Service\CurrencyExchangeRate\CurrencyExchangeRateExternalSourceProviderInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Throwable;

#[AsCommand('rates:sync')]
class SyncRatesCommand extends AbstractLockedCommand
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly CurrencyExchangeRateExternalSourceProviderInterface $exchangeRateExternalSourceProvider
    ) {
        parent::__construct();
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->exchangeRateExternalSourceProvider->provide() as $rate) {
            try {
                $command = new CreateCurrencyExchangeRateCommand(
                    $rate->from,
                    $rate->to,
                    $rate->rate
                );

                $this->commandBus->execute($command);
            } catch (Throwable $e) { // in case of sync transports, exception must be handled manually
                if (isset($command)) {
                    $event = new WorkerMessageFailedEvent(
                        new Envelope($command),
                        'sync://',
                        $e
                    );

                    $this->eventDispatcher->dispatch($event);
                    continue;
                }

                throw $e;
            }
        }

        return Command::SUCCESS;
    }
}
