<?php

declare(strict_types=1);

namespace App\Infrastructure\Messenger\EventSubscriber\HandleFailedMessageAfterRetriesEventSubscriber;

use App\Infrastructure\Messenger\EventSubscriber\HandleFailedMessageAfterRetriesEventSubscriber\FailedMessageAfterRetriesHandler\FailedMessageAfterRetriesHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;

readonly class MessageFailedAfterRetriesEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private FailedMessageAfterRetriesHandler $failedMessageAfterRetriesHandler
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            WorkerMessageFailedEvent::class => 'onMessageFailed',
        ];
    }

    public function onMessageFailed(WorkerMessageFailedEvent $event): void
    {
        if ($event->willRetry()) {
            return;
        }

        $this->failedMessageAfterRetriesHandler->handle(
            $event->getEnvelope()->getMessage(),
            $event->getThrowable()
        );
    }
}
