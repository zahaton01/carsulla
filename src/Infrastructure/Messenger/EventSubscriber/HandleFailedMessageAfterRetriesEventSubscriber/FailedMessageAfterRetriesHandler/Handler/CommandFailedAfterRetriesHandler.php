<?php

declare(strict_types=1);

namespace App\Infrastructure\Messenger\EventSubscriber\HandleFailedMessageAfterRetriesEventSubscriber\FailedMessageAfterRetriesHandler\Handler;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Throwable;

#[AutoconfigureTag(self::TAG)]
interface CommandFailedAfterRetriesHandler
{
    public const string TAG = 'command_failed_after_retries_handler';

    public function supports(mixed $message): bool;

    public function handle(mixed $message, Throwable $e): void;
}
