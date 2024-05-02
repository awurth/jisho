<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240501173152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Base schema';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE dictionary (
          id UUID NOT NULL,
          name VARCHAR(255) NOT NULL,
          owner_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_1FA0E5267E3C61F9 ON dictionary (owner_id)');
        $this->addSql('CREATE TABLE french_entry (
          id UUID NOT NULL,
          value VARCHAR(255) NOT NULL,
          dictionary_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_4F7D0422AF5E5B3C ON french_entry (dictionary_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4F7D0422AF5E5B3C1D775834 ON french_entry (dictionary_id, value)');
        $this->addSql('CREATE TABLE japanese_entry (
          id UUID NOT NULL,
          value VARCHAR(255) NOT NULL,
          notes TEXT DEFAULT NULL,
          dictionary_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_DB7432F4AF5E5B3C ON japanese_entry (dictionary_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DB7432F4AF5E5B3C1D775834 ON japanese_entry (dictionary_id, value)');
        $this->addSql('CREATE TABLE japanese_entry_tag (
          id UUID NOT NULL,
          japanese_entry_id UUID NOT NULL,
          tag_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_3A5BA9D96E94FFB3 ON japanese_entry_tag (japanese_entry_id)');
        $this->addSql('CREATE INDEX IDX_3A5BA9D9BAD26311 ON japanese_entry_tag (tag_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3A5BA9D96E94FFB3BAD26311 ON japanese_entry_tag (japanese_entry_id, tag_id)');
        $this->addSql('CREATE TABLE japanese_french_association (
          id UUID NOT NULL,
          japanese_entry_id UUID NOT NULL,
          french_entry_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_DDC487A06E94FFB3 ON japanese_french_association (japanese_entry_id)');
        $this->addSql('CREATE INDEX IDX_DDC487A017509F74 ON japanese_french_association (french_entry_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DDC487A06E94FFB317509F74 ON japanese_french_association (
          japanese_entry_id, french_entry_id
        )');
        $this->addSql('CREATE TABLE tag (
          id UUID NOT NULL,
          name VARCHAR(255) NOT NULL,
          color VARCHAR(6) DEFAULT NULL,
          dictionary_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_389B783AF5E5B3C ON tag (dictionary_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B783AF5E5B3C5E237E06 ON tag (dictionary_id, name)');
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
          dictionary
        ADD
          CONSTRAINT FK_1FA0E5267E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          french_entry
        ADD
          CONSTRAINT FK_4F7D0422AF5E5B3C FOREIGN KEY (dictionary_id) REFERENCES dictionary (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          japanese_entry
        ADD
          CONSTRAINT FK_DB7432F4AF5E5B3C FOREIGN KEY (dictionary_id) REFERENCES dictionary (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          japanese_entry_tag
        ADD
          CONSTRAINT FK_3A5BA9D96E94FFB3 FOREIGN KEY (japanese_entry_id) REFERENCES japanese_entry (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          japanese_entry_tag
        ADD
          CONSTRAINT FK_3A5BA9D9BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          japanese_french_association
        ADD
          CONSTRAINT FK_DDC487A06E94FFB3 FOREIGN KEY (japanese_entry_id) REFERENCES japanese_entry (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          japanese_french_association
        ADD
          CONSTRAINT FK_DDC487A017509F74 FOREIGN KEY (french_entry_id) REFERENCES french_entry (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          tag
        ADD
          CONSTRAINT FK_389B783AF5E5B3C FOREIGN KEY (dictionary_id) REFERENCES dictionary (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE dictionary DROP CONSTRAINT FK_1FA0E5267E3C61F9');
        $this->addSql('ALTER TABLE french_entry DROP CONSTRAINT FK_4F7D0422AF5E5B3C');
        $this->addSql('ALTER TABLE japanese_entry DROP CONSTRAINT FK_DB7432F4AF5E5B3C');
        $this->addSql('ALTER TABLE japanese_entry_tag DROP CONSTRAINT FK_3A5BA9D96E94FFB3');
        $this->addSql('ALTER TABLE japanese_entry_tag DROP CONSTRAINT FK_3A5BA9D9BAD26311');
        $this->addSql('ALTER TABLE japanese_french_association DROP CONSTRAINT FK_DDC487A06E94FFB3');
        $this->addSql('ALTER TABLE japanese_french_association DROP CONSTRAINT FK_DDC487A017509F74');
        $this->addSql('ALTER TABLE tag DROP CONSTRAINT FK_389B783AF5E5B3C');
        $this->addSql('DROP TABLE dictionary');
        $this->addSql('DROP TABLE french_entry');
        $this->addSql('DROP TABLE japanese_entry');
        $this->addSql('DROP TABLE japanese_entry_tag');
        $this->addSql('DROP TABLE japanese_french_association');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE "user"');
    }
}
