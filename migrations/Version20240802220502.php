<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240802220502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE proxy_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE proxy_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE proxy (id INT NOT NULL, name VARCHAR(32) NOT NULL, url VARCHAR(32) NOT NULL, swapped_url VARCHAR(32) NOT NULL, method VARCHAR(16) NOT NULL, active BOOLEAN NOT NULL, weight INT NOT NULL, parameters_bag_type VARCHAR(255) NOT NULL, additional_headers VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE proxy_log (id INT NOT NULL, proxy VARCHAR(255) NOT NULL, request_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, response VARCHAR(255) NOT NULL, response_code INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE proxy_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE proxy_log_id_seq CASCADE');
        $this->addSql('DROP TABLE proxy');
        $this->addSql('DROP TABLE proxy_log');
    }
}
