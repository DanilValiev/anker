<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241003223234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE endpoint_data_response_variant_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE endpoint_data_response_variant (id INT NOT NULL, endpoint_data_id INT NOT NULL, data TEXT NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_691ED87B1ACA5812 ON endpoint_data_response_variant (endpoint_data_id)');
        $this->addSql('ALTER TABLE endpoint_data_response_variant ADD CONSTRAINT FK_691ED87B1ACA5812 FOREIGN KEY (endpoint_data_id) REFERENCES endpoint_data (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE endpoint_data DROP CONSTRAINT fk_fa3f0914fcaee21f');
        $this->addSql('DROP INDEX idx_fa3f0914fcaee21f');
        $this->addSql('ALTER TABLE endpoint_data ADD name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE endpoint_data DROP data');
        $this->addSql('ALTER TABLE endpoint_data RENAME COLUMN scopes_endpoints_id TO endpoint_id');
        $this->addSql('ALTER TABLE endpoint_data ADD CONSTRAINT FK_FA3F091421AF7E36 FOREIGN KEY (endpoint_id) REFERENCES endpoint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FA3F091421AF7E36 ON endpoint_data (endpoint_id)');
        $this->addSql('ALTER TABLE endpoint_param DROP CONSTRAINT fk_6acc54ecfcaee21f');
        $this->addSql('DROP INDEX idx_6acc54ecfcaee21f');
        $this->addSql('ALTER TABLE endpoint_param RENAME COLUMN scopes_endpoints_id TO endpoint_id');
        $this->addSql('ALTER TABLE endpoint_param ADD CONSTRAINT FK_6ACC54EC21AF7E36 FOREIGN KEY (endpoint_id) REFERENCES endpoint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6ACC54EC21AF7E36 ON endpoint_param (endpoint_id)');
        $this->addSql('ALTER TABLE process_log ALTER response TYPE TEXT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE endpoint_data_response_variant_id_seq CASCADE');
        $this->addSql('ALTER TABLE endpoint_data_response_variant DROP CONSTRAINT FK_691ED87B1ACA5812');
        $this->addSql('DROP TABLE endpoint_data_response_variant');
        $this->addSql('ALTER TABLE endpoint_param DROP CONSTRAINT FK_6ACC54EC21AF7E36');
        $this->addSql('DROP INDEX IDX_6ACC54EC21AF7E36');
        $this->addSql('ALTER TABLE endpoint_param RENAME COLUMN endpoint_id TO scopes_endpoints_id');
        $this->addSql('ALTER TABLE endpoint_param ADD CONSTRAINT fk_6acc54ecfcaee21f FOREIGN KEY (scopes_endpoints_id) REFERENCES endpoint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6acc54ecfcaee21f ON endpoint_param (scopes_endpoints_id)');
        $this->addSql('ALTER TABLE process_log ALTER response TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE endpoint_data DROP CONSTRAINT FK_FA3F091421AF7E36');
        $this->addSql('DROP INDEX IDX_FA3F091421AF7E36');
        $this->addSql('ALTER TABLE endpoint_data ADD data TEXT NOT NULL');
        $this->addSql('ALTER TABLE endpoint_data DROP name');
        $this->addSql('ALTER TABLE endpoint_data RENAME COLUMN endpoint_id TO scopes_endpoints_id');
        $this->addSql('ALTER TABLE endpoint_data ADD CONSTRAINT fk_fa3f0914fcaee21f FOREIGN KEY (scopes_endpoints_id) REFERENCES endpoint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_fa3f0914fcaee21f ON endpoint_data (scopes_endpoints_id)');
    }
}
