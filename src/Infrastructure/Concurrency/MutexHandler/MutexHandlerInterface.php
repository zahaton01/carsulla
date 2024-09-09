<?php

namespace App\Infrastructure\Concurrency\MutexHandler;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(self::TAG)]
interface MutexHandlerInterface
{
    public const string TAG = 'mutex_handler';

    public function supports(mixed $entity): bool;
    public function waitAndLock(mixed $entity): void;
    public function release(mixed $entity): void;
}
