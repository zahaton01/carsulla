<?php

declare(strict_types=1);

namespace App\CLI;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Lock\Exception\LockConflictedException;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\SemaphoreStore;

abstract class AbstractLockedCommand extends Command
{
    private const string LOCK_PREFIX = 'command:';

    protected SymfonyStyle $io;

    abstract protected function doExecute(InputInterface $input, OutputInterface $output): int;

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $store = new SemaphoreStore();
        $factory = new LockFactory($store);

        $lock = $factory->createLock($this->getLockKey(), 1800);

        if ($lock->isAcquired()) {
            return $this->skipLaunch();
        }

        try {
            if ($lock->acquire()) {
                $exitCode = $this->doExecute($input, $output);
                $lock->release();

                return $exitCode;
            }
        } catch (LockConflictedException) {
            return $this->skipLaunch();
        }

        return $this->skipLaunch();
    }

    protected function getLockKey(): string
    {
        return self::LOCK_PREFIX . __DIR__ . '_' . $this->getName();
    }

    private function skipLaunch(): int
    {
        $message = $this->getName() . " command launch is skipped, previous instance is still running";
        $this->io->info($message);

        return Command::SUCCESS;
    }
}
