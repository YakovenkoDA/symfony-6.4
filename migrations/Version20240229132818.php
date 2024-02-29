<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229132818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ALTER created TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING TO_TIMESTAMP(created::integer)');
        $this->addSql('ALTER TABLE "user" ALTER updated TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING TO_TIMESTAMP(updated::integer)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ALTER created TYPE INT USING TO_I(created::TIMESTAMP)');
        $this->addSql('ALTER TABLE "user" ALTER updated TYPE INT USING updated::TIMESTAMP');
    }
}
