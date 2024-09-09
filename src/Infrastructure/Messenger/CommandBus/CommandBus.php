<?php
declare(strict_types=1);

namespace App\Infrastructure\Messenger\CommandBus;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

readonly class CommandBus implements CommandBusInterface
{
    public function __construct(
        private MessageBusInterface $messageBus
    ) {}

    public function execute($command)
    {
        $envelope = $this->messageBus->dispatch($command);

        $handledStamp = $envelope->last(HandledStamp::class);
        return $handledStamp?->getResult();
    }
}
