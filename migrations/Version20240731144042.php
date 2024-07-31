<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240731144042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE api_scope_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE endpoint_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE endpoint_data_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE endpoint_param_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ext_log_entries_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE api_scope (id INT NOT NULL, slug VARCHAR(16) NOT NULL, description TEXT DEFAULT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE endpoint (id INT NOT NULL, api_scopes_id INT NOT NULL, slug VARCHAR(16) NOT NULL, active BOOLEAN NOT NULL, sleep_time INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C4420F7BCB13333D ON endpoint (api_scopes_id)');
        $this->addSql('CREATE TABLE endpoint_data (id INT NOT NULL, scopes_endpoints_id INT DEFAULT NULL, expression VARCHAR(255) DEFAULT NULL, data JSON NOT NULL, active BOOLEAN NOT NULL, status_code INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FA3F0914FCAEE21F ON endpoint_data (scopes_endpoints_id)');
        $this->addSql('CREATE TABLE endpoint_param (id INT NOT NULL, scopes_endpoints_id INT DEFAULT NULL, name VARCHAR(32) NOT NULL, type VARCHAR(16) NOT NULL, whitelist JSON DEFAULT NULL, regex VARCHAR(255) DEFAULT NULL, error_message JSON DEFAULT NULL, required BOOLEAN NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6ACC54ECFCAEE21F ON endpoint_param (scopes_endpoints_id)');
        $this->addSql('CREATE TABLE ext_log_entries (id INT NOT NULL, action VARCHAR(8) NOT NULL, logged_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(191) NOT NULL, version INT NOT NULL, data TEXT DEFAULT NULL, username VARCHAR(191) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX log_class_lookup_idx ON ext_log_entries (object_class)');
        $this->addSql('CREATE INDEX log_date_lookup_idx ON ext_log_entries (logged_at)');
        $this->addSql('CREATE INDEX log_user_lookup_idx ON ext_log_entries (username)');
        $this->addSql('CREATE INDEX log_version_lookup_idx ON ext_log_entries (object_id, object_class, version)');
        $this->addSql('COMMENT ON COLUMN ext_log_entries.data IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE endpoint ADD CONSTRAINT FK_C4420F7BCB13333D FOREIGN KEY (api_scopes_id) REFERENCES api_scope (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE endpoint_data ADD CONSTRAINT FK_FA3F0914FCAEE21F FOREIGN KEY (scopes_endpoints_id) REFERENCES endpoint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE endpoint_param ADD CONSTRAINT FK_6ACC54ECFCAEE21F FOREIGN KEY (scopes_endpoints_id) REFERENCES endpoint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE api_scope_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE endpoint_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE endpoint_data_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE endpoint_param_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ext_log_entries_id_seq CASCADE');
        $this->addSql('ALTER TABLE endpoint DROP CONSTRAINT FK_C4420F7BCB13333D');
        $this->addSql('ALTER TABLE endpoint_data DROP CONSTRAINT FK_FA3F0914FCAEE21F');
        $this->addSql('ALTER TABLE endpoint_param DROP CONSTRAINT FK_6ACC54ECFCAEE21F');
        $this->addSql('DROP TABLE api_scope');
        $this->addSql('DROP TABLE endpoint');
        $this->addSql('DROP TABLE endpoint_data');
        $this->addSql('DROP TABLE endpoint_param');
        $this->addSql('DROP TABLE ext_log_entries');
    }
}
