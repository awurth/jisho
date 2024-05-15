<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240515160814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the base schema';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE deck (
          id UUID NOT NULL,
          name VARCHAR(50) NOT NULL,
          created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          owner_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_4FAC36377E3C61F9 ON deck (owner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FAC36377E3C61F95E237E06 ON deck (owner_id, name)');
        $this->addSql('CREATE TABLE deck_entry (
          id UUID NOT NULL,
          added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          deck_id UUID NOT NULL,
          entry_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_EA7C679F111948DC ON deck_entry (deck_id)');
        $this->addSql('CREATE INDEX IDX_EA7C679FBA364942 ON deck_entry (entry_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EA7C679F111948DCBA364942 ON deck_entry (deck_id, entry_id)');
        $this->addSql('CREATE TABLE "user" (
          id UUID NOT NULL,
          email VARCHAR(180) NOT NULL,
          roles JSON NOT NULL,
          name VARCHAR(255) NOT NULL,
          avatar_url VARCHAR(255) DEFAULT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE
          deck
        ADD
          CONSTRAINT FK_4FAC36377E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          deck_entry
        ADD
          CONSTRAINT FK_EA7C679F111948DC FOREIGN KEY (deck_id) REFERENCES deck (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          deck_entry
        ADD
          CONSTRAINT FK_EA7C679FBA364942 FOREIGN KEY (entry_id) REFERENCES entry (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE deck DROP CONSTRAINT FK_4FAC36377E3C61F9');
        $this->addSql('ALTER TABLE deck_entry DROP CONSTRAINT FK_EA7C679F111948DC');
        $this->addSql('ALTER TABLE deck_entry DROP CONSTRAINT FK_EA7C679FBA364942');
        $this->addSql('DROP TABLE deck');
        $this->addSql('DROP TABLE deck_entry');
        $this->addSql('DROP TABLE "user"');
    }
}
