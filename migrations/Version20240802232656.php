<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240802232656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE proxy ADD next_proxy_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE proxy DROP next_proxy');
        $this->addSql('ALTER TABLE proxy ADD CONSTRAINT FK_7372C9BE35A826A0 FOREIGN KEY (next_proxy_id) REFERENCES proxy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7372C9BE35A826A0 ON proxy (next_proxy_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE proxy DROP CONSTRAINT FK_7372C9BE35A826A0');
        $this->addSql('DROP INDEX IDX_7372C9BE35A826A0');
        $this->addSql('ALTER TABLE proxy ADD next_proxy VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE proxy DROP next_proxy_id');
    }
}
