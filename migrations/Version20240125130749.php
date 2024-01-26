<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240125130749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD password VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD email VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD created INT NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD updated INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP password');
        $this->addSql('ALTER TABLE "user" DROP email');
        $this->addSql('ALTER TABLE "user" DROP created');
        $this->addSql('ALTER TABLE "user" DROP updated');
    }
}
