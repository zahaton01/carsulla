<?php

declare(strict_types=1);

namespace App\Infrastructure\Messenger\EventSubscriber\HandleFailedMessageAfterRetriesEventSubscriber\FailedMessageAfterRetriesHandler\Handler;

use App\Application\CurrencyExchangeRate\CreateCurrencyExchangeRateCommand;
use Psr\Log\LoggerInterface;
use Throwable;

readonly class CreateExchangeRateCommandFailedHandler implements CommandFailedAfterRetriesHandler
{
    public function __construct(
        private LoggerInterface $logger
    ) {

    }

    public function supports(mixed $message): bool
    {
        return $message instanceof CreateCurrencyExchangeRateCommand;
    }

    /**
     * @param CreateCurrencyExchangeRateCommand $message
     */
    public function handle(mixed $message, Throwable $e): void
    {
        $this->logger->critical($e->getMessage());
    }
}
