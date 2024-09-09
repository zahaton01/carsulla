<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class CurrencyExchangeRate
{
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(type: Types::INTEGER)]
    private ?int $id = 0;

    #[Column(type: Types::STRING, length: 10)]
    private string $currencyFrom;

    #[Column(type: Types::STRING, length: 10)]
    private string $currencyTo;

    #[Column(type: Types::FLOAT)]
    private float $rate;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeInterface $createdAt;

    public function __construct(
        string $from,
        string $to,
        float $rate
    ) {
        $this->currencyFrom = $from;
        $this->currencyTo = $to;
        $this->rate = $rate;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrencyFrom(): string
    {
        return $this->currencyFrom;
    }

    public function getCurrencyTo(): string
    {
        return $this->currencyTo;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
