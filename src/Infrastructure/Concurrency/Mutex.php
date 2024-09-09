<?php

declare(strict_types=1);

namespace App\Infrastructure\Concurrency;


use App\Infrastructure\Concurrency\MutexHandler\MutexHandlerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class Mutex implements MutexInterface
{
    public function __construct(
        /**
         * @var MutexHandlerInterface[]
         */
        #[AutowireIterator(MutexHandlerInterface::TAG)]
        private iterable $handlers
    ) {
    }

    public function waitAndLock(mixed $entity): void
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($entity)) {
                $handler->waitAndLock($entity);
                break;
            }
        }
    }

    public function release(mixed $entity): void
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($entity)) {
                $handler->release($entity);
                break;
            }
        }
    }
}
