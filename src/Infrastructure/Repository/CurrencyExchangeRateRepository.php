<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\CurrencyExchangeRate;
use App\Domain\Repository\CurrencyExchangeRateRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CurrencyExchangeRateRepository extends ServiceEntityRepository implements CurrencyExchangeRateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyExchangeRate::class);
    }

    public function save(CurrencyExchangeRate $currencyExchangeRate): void
    {
        $this->getEntityManager()->persist($currencyExchangeRate);
        $this->getEntityManager()->flush();
    }

    public function findLatestByCurrencyPair(string $from, string $to): ?CurrencyExchangeRate
    {
        return $this->findOneBy(['currencyFrom' => $from, 'currencyTo' => $to], ['createdAt' => 'desc']);
    }
}
