<?php
declare(strict_types=1);

namespace App\Infrastructure\Messenger\CommandBus;

interface CommandBusInterface
{
    public function execute($command);
}
