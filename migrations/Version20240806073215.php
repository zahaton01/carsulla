<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240806073215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE doctor_visit_slots_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE doctors_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE doctor_visit_slots (id INT NOT NULL, doctor_id INT DEFAULT NULL, slot_start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, slot_end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_booked BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3AFE7A4A87F4FB17 ON doctor_visit_slots (doctor_id)');
        $this->addSql('CREATE INDEX IDX_3AFE7A4AF5FE03FA ON doctor_visit_slots (doctor_id, slot_start_date)');
        $this->addSql('COMMENT ON COLUMN doctor_visit_slots.slot_start_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN doctor_visit_slots.slot_end_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN doctor_visit_slots.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE doctors (id INT NOT NULL, external_id VARCHAR(255) NOT NULL, full_name VARCHAR(255) NOT NULL, entity_data_sync_fail_log JSONB DEFAULT NULL, last_synced_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B67687BE9F75D7B0 ON doctors (external_id)');
        $this->addSql('COMMENT ON COLUMN doctors.entity_data_sync_fail_log IS \'(DC2Type:json_document)\'');
        $this->addSql('COMMENT ON COLUMN doctors.last_synced_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN doctors.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE doctor_visit_slots ADD CONSTRAINT FK_3AFE7A4A87F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctors (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE doctor_visit_slots_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE doctors_id_seq CASCADE');
        $this->addSql('ALTER TABLE doctor_visit_slots DROP CONSTRAINT FK_3AFE7A4A87F4FB17');
        $this->addSql('DROP TABLE doctor_visit_slots');
        $this->addSql('DROP TABLE doctors');
    }
}
