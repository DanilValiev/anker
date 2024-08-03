<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240801150124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE process_log ALTER scope_id DROP NOT NULL');
        $this->addSql('ALTER TABLE process_log ALTER endpoint_id DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE process_log ALTER scope_id SET NOT NULL');
        $this->addSql('ALTER TABLE process_log ALTER endpoint_id SET NOT NULL');
    }
}
