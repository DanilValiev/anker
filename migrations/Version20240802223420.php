<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240802223420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE proxy ADD endpoint_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE proxy ADD CONSTRAINT FK_7372C9BE21AF7E36 FOREIGN KEY (endpoint_id) REFERENCES endpoint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7372C9BE21AF7E36 ON proxy (endpoint_id)');
        $this->addSql('ALTER TABLE proxy_log ADD endpoint_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE proxy_log ADD CONSTRAINT FK_7582DEAB21AF7E36 FOREIGN KEY (endpoint_id) REFERENCES endpoint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7582DEAB21AF7E36 ON proxy_log (endpoint_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE proxy_log DROP CONSTRAINT FK_7582DEAB21AF7E36');
        $this->addSql('DROP INDEX IDX_7582DEAB21AF7E36');
        $this->addSql('ALTER TABLE proxy_log DROP endpoint_id');
        $this->addSql('ALTER TABLE proxy DROP CONSTRAINT FK_7372C9BE21AF7E36');
        $this->addSql('DROP INDEX IDX_7372C9BE21AF7E36');
        $this->addSql('ALTER TABLE proxy DROP endpoint_id');
    }
}
