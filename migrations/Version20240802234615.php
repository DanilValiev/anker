<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240802234615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE proxy_log ADD proxy_id INT NOT NULL');
        $this->addSql('ALTER TABLE proxy_log DROP proxy');
        $this->addSql('ALTER TABLE proxy_log ADD CONSTRAINT FK_7582DEABDB26A4E FOREIGN KEY (proxy_id) REFERENCES proxy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7582DEABDB26A4E ON proxy_log (proxy_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE proxy_log DROP CONSTRAINT FK_7582DEABDB26A4E');
        $this->addSql('DROP INDEX IDX_7582DEABDB26A4E');
        $this->addSql('ALTER TABLE proxy_log ADD proxy VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE proxy_log DROP proxy_id');
    }
}
