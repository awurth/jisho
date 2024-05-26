<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240526101041 extends AbstractMigration
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
        $this->addSql('CREATE TABLE entry (id UUID NOT NULL, sequence_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2B219D7098FB19AE ON entry (sequence_id)');
        $this->addSql('CREATE TABLE kanji_element (
          id UUID NOT NULL,
          value VARCHAR(255) NOT NULL,
          info VARCHAR(255) DEFAULT NULL,
          priority VARCHAR(255) DEFAULT NULL,
          entry_id UUID DEFAULT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_31347D4DBA364942 ON kanji_element (entry_id)');
        $this->addSql('CREATE TABLE reading_element (
          id UUID NOT NULL,
          kana VARCHAR(255) NOT NULL,
          romaji VARCHAR(255) NOT NULL,
          info VARCHAR(255) DEFAULT NULL,
          priority VARCHAR(255) DEFAULT NULL,
          not_true_kanji_reading BOOLEAN NOT NULL,
          kanji_elements JSON NOT NULL,
          entry_id UUID DEFAULT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_5D9CD0CBA364942 ON reading_element (entry_id)');
        $this->addSql('CREATE TABLE sense (
          id UUID NOT NULL,
          parts_of_speech JSON NOT NULL,
          field_of_application VARCHAR(255) DEFAULT NULL,
          dialect VARCHAR(255) DEFAULT NULL,
          misc VARCHAR(255) DEFAULT NULL,
          info VARCHAR(255) DEFAULT NULL,
          kanji_elements JSON NOT NULL,
          reading_elements JSON NOT NULL,
          referenced_elements JSON NOT NULL,
          antonyms JSON NOT NULL,
          entry_id UUID DEFAULT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_F2B33FBBA364942 ON sense (entry_id)');
        $this->addSql('CREATE TABLE tag (
          id UUID NOT NULL,
          name VARCHAR(255) NOT NULL,
          color VARCHAR(6) DEFAULT NULL,
          deck_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_389B783111948DC ON tag (deck_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B783111948DC5E237E06 ON tag (deck_id, name)');
        $this->addSql('CREATE TABLE translation (
          id UUID NOT NULL,
          value TEXT NOT NULL,
          language VARCHAR(255) NOT NULL,
          sense_id UUID DEFAULT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_B469456F8707C57E ON translation (sense_id)');
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
        $this->addSql('ALTER TABLE
          kanji_element
        ADD
          CONSTRAINT FK_31347D4DBA364942 FOREIGN KEY (entry_id) REFERENCES entry (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          reading_element
        ADD
          CONSTRAINT FK_5D9CD0CBA364942 FOREIGN KEY (entry_id) REFERENCES entry (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          sense
        ADD
          CONSTRAINT FK_F2B33FBBA364942 FOREIGN KEY (entry_id) REFERENCES entry (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          tag
        ADD
          CONSTRAINT FK_389B783111948DC FOREIGN KEY (deck_id) REFERENCES deck (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          translation
        ADD
          CONSTRAINT FK_B469456F8707C57E FOREIGN KEY (sense_id) REFERENCES sense (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE deck DROP CONSTRAINT FK_4FAC36377E3C61F9');
        $this->addSql('ALTER TABLE deck_entry DROP CONSTRAINT FK_EA7C679F111948DC');
        $this->addSql('ALTER TABLE deck_entry DROP CONSTRAINT FK_EA7C679FBA364942');
        $this->addSql('ALTER TABLE kanji_element DROP CONSTRAINT FK_31347D4DBA364942');
        $this->addSql('ALTER TABLE reading_element DROP CONSTRAINT FK_5D9CD0CBA364942');
        $this->addSql('ALTER TABLE sense DROP CONSTRAINT FK_F2B33FBBA364942');
        $this->addSql('ALTER TABLE tag DROP CONSTRAINT FK_389B783111948DC');
        $this->addSql('ALTER TABLE translation DROP CONSTRAINT FK_B469456F8707C57E');
        $this->addSql('DROP TABLE deck');
        $this->addSql('DROP TABLE deck_entry');
        $this->addSql('DROP TABLE entry');
        $this->addSql('DROP TABLE kanji_element');
        $this->addSql('DROP TABLE reading_element');
        $this->addSql('DROP TABLE sense');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE translation');
        $this->addSql('DROP TABLE "user"');
    }
}
