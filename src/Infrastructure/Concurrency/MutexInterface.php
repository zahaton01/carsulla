<?php

namespace App\Infrastructure\Concurrency;

interface MutexInterface
{
    public function waitAndLock(mixed $entity): void;
    public function release(mixed $entity): void;
}
