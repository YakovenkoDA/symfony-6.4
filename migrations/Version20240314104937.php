<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240314104937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE friendship_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE friendship (id INT NOT NULL, sender_id INT NOT NULL, recipient_id INT NOT NULL, status INT NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, accepted TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7234A45FF624B39D ON friendship (sender_id)');
        $this->addSql('CREATE INDEX IDX_7234A45FE92F8F78 ON friendship (recipient_id)');
        $this->addSql('ALTER TABLE friendship ADD CONSTRAINT FK_7234A45FF624B39D FOREIGN KEY (sender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE friendship ADD CONSTRAINT FK_7234A45FE92F8F78 FOREIGN KEY (recipient_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE friendship_id_seq CASCADE');
        $this->addSql('ALTER TABLE friendship DROP CONSTRAINT FK_7234A45FF624B39D');
        $this->addSql('ALTER TABLE friendship DROP CONSTRAINT FK_7234A45FE92F8F78');
        $this->addSql('DROP TABLE friendship');
    }
}
