<?php

declare(strict_types=1);

namespace App\Infrastructure\Db\DataFixtures;

use App\Domain\Entity\CurrencyExchangeRate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CurrencyRateFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $rate = new CurrencyExchangeRate('EUR', 'USD', 0.9);
        $manager->persist($rate);
        $manager->flush();
    }
}
