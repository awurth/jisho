<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250317111058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the base schema';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE card (
          id UUID NOT NULL,
          added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          deck_id UUID NOT NULL,
          entry_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_161498D3111948DC ON card (deck_id)');
        $this->addSql('CREATE INDEX IDX_161498D3BA364942 ON card (entry_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_161498D3111948DCBA364942 ON card (deck_id, entry_id)');
        $this->addSql('CREATE TABLE deck (
          id UUID NOT NULL,
          name VARCHAR(50) NOT NULL,
          created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          owner_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_4FAC36377E3C61F9 ON deck (owner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FAC36377E3C61F95E237E06 ON deck (owner_id, name)');
        $this->addSql('CREATE TABLE dictionary_entry (
          id UUID NOT NULL,
          sequence_id INT NOT NULL,
          kanji_elements JSONB NOT NULL,
          reading_elements JSONB NOT NULL,
          senses JSONB NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CAC9222098FB19AE ON dictionary_entry (sequence_id)');
        $this->addSql('CREATE TABLE question (
          id UUID NOT NULL,
          position INT NOT NULL,
          answered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
          answer VARCHAR(255) NOT NULL,
          skipped_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
          quiz_id UUID NOT NULL,
          card_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_B6F7494E853CD175 ON question (quiz_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E4ACC9A20 ON question (card_id)');
        $this->addSql('CREATE TABLE quiz (
          id UUID NOT NULL,
          max_questions INT NOT NULL,
          number_of_questions INT DEFAULT NULL,
          score INT DEFAULT NULL,
          created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
          ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
          deck_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_A412FA92111948DC ON quiz (deck_id)');
        $this->addSql('CREATE TABLE tag (
          id UUID NOT NULL,
          name VARCHAR(255) NOT NULL,
          color VARCHAR(6) NOT NULL,
          deck_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_389B783111948DC ON tag (deck_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B783111948DC5E237E06 ON tag (deck_id, name)');
        $this->addSql('CREATE TABLE "user" (
          id UUID NOT NULL,
          email VARCHAR(180) NOT NULL,
          roles JSON NOT NULL,
          name VARCHAR(255) NOT NULL,
          avatar_url VARCHAR(255) NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE
          card
        ADD
          CONSTRAINT FK_161498D3111948DC FOREIGN KEY (deck_id) REFERENCES deck (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          card
        ADD
          CONSTRAINT FK_161498D3BA364942 FOREIGN KEY (entry_id) REFERENCES dictionary_entry (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          deck
        ADD
          CONSTRAINT FK_4FAC36377E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          question
        ADD
          CONSTRAINT FK_B6F7494E853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          question
        ADD
          CONSTRAINT FK_B6F7494E4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          quiz
        ADD
          CONSTRAINT FK_A412FA92111948DC FOREIGN KEY (deck_id) REFERENCES deck (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          tag
        ADD
          CONSTRAINT FK_389B783111948DC FOREIGN KEY (deck_id) REFERENCES deck (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE card DROP CONSTRAINT FK_161498D3111948DC');
        $this->addSql('ALTER TABLE card DROP CONSTRAINT FK_161498D3BA364942');
        $this->addSql('ALTER TABLE deck DROP CONSTRAINT FK_4FAC36377E3C61F9');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494E853CD175');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494E4ACC9A20');
        $this->addSql('ALTER TABLE quiz DROP CONSTRAINT FK_A412FA92111948DC');
        $this->addSql('ALTER TABLE tag DROP CONSTRAINT FK_389B783111948DC');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE deck');
        $this->addSql('DROP TABLE dictionary_entry');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE "user"');
    }
}
