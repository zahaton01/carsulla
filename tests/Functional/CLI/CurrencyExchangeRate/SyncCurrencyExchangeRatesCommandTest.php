<?php

declare(strict_types=1);

namespace App\Tests\Functional\CLI\CurrencyExchangeRate;

use App\Domain\Entity\CurrencyExchangeRate;
use App\Tests\Functional\AppTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class SyncCurrencyExchangeRatesCommandTest extends AppTestCase
{
    public function testSuccess(): void
    {
        $previousCount = self::$em->getRepository(CurrencyExchangeRate::class)->count([]);
        $this->executeCommand();

        $this->assertNotSame(
            self::$em->getRepository(CurrencyExchangeRate::class)->count([]),
            $previousCount
        );
    }

    private function executeCommand(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('rates:sync');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);
        $commandTester->assertCommandIsSuccessful();
    }
}
