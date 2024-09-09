<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240909132826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE currency_exchange_rate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE currency_exchange_rate (id INT NOT NULL, currency_from VARCHAR(10) NOT NULL, currency_to VARCHAR(10) NOT NULL, rate DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN currency_exchange_rate.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE currency_exchange_rate_id_seq CASCADE');
        $this->addSql('DROP TABLE currency_exchange_rate');
    }
}
