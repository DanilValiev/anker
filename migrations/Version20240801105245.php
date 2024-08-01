<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240801105245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE process_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE process_log (id INT NOT NULL, scope_id INT NOT NULL, endpoint_id INT NOT NULL, method VARCHAR(255) NOT NULL, incoming_headers JSON NOT NULL, incoming_params JSON NOT NULL, user_ips JSON NOT NULL, request_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, response VARCHAR(255) NOT NULL, response_code INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B3FD7786682B5931 ON process_log (scope_id)');
        $this->addSql('CREATE INDEX IDX_B3FD778621AF7E36 ON process_log (endpoint_id)');
        $this->addSql('ALTER TABLE process_log ADD CONSTRAINT FK_B3FD7786682B5931 FOREIGN KEY (scope_id) REFERENCES api_scope (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE process_log ADD CONSTRAINT FK_B3FD778621AF7E36 FOREIGN KEY (endpoint_id) REFERENCES endpoint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE process_log_id_seq CASCADE');
        $this->addSql('ALTER TABLE process_log DROP CONSTRAINT FK_B3FD7786682B5931');
        $this->addSql('ALTER TABLE process_log DROP CONSTRAINT FK_B3FD778621AF7E36');
        $this->addSql('DROP TABLE process_log');
    }
}
