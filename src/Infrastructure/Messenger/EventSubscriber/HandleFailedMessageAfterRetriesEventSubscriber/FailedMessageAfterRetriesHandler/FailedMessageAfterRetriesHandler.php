<?php

declare(strict_types=1);

namespace App\Infrastructure\Messenger\EventSubscriber\HandleFailedMessageAfterRetriesEventSubscriber\FailedMessageAfterRetriesHandler;

use App\Infrastructure\Messenger\EventSubscriber\HandleFailedMessageAfterRetriesEventSubscriber\FailedMessageAfterRetriesHandler\Handler\CommandFailedAfterRetriesHandler;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Throwable;

readonly class FailedMessageAfterRetriesHandler
{
    public function __construct(
        /**
         * @var CommandFailedAfterRetriesHandler[]
         */
        #[AutowireIterator(CommandFailedAfterRetriesHandler::TAG)]
        private iterable $handlers,
    ) {

    }

    public function handle(mixed $message, Throwable $e): void
    {
        if ($e instanceof HandlerFailedException) {
            while ($e instanceof HandlerFailedException) {
                $e = $e->getPrevious();
            }
        }

        foreach ($this->handlers as $handler) {
            if ($handler->supports($message)) {
                $handler->handle($message, $e);
            }
        }
    }
}
