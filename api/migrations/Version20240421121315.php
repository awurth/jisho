<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240421121315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Base Schema';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE dictionary (
          id UUID NOT NULL,
          name VARCHAR(255) NOT NULL,
          owner_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX idx_1fa0e5267e3c61f9 ON dictionary (owner_id)');
        $this->addSql('ALTER TABLE
          dictionary
        ADD
          CONSTRAINT fk_1fa0e5267e3c61f9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('CREATE TABLE japanese_entry (
          id UUID NOT NULL,
          value VARCHAR(255) NOT NULL,
          dictionary_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX uniq_db7432f4af5e5b3c1d775834 ON japanese_entry (dictionary_id, value)');
        $this->addSql('CREATE INDEX idx_db7432f4af5e5b3c ON japanese_entry (dictionary_id)');
        $this->addSql('ALTER TABLE
          japanese_entry
        ADD
          CONSTRAINT fk_db7432f4af5e5b3c FOREIGN KEY (dictionary_id) REFERENCES dictionary (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('CREATE TABLE tag (
          id UUID NOT NULL,
          name VARCHAR(255) NOT NULL,
          color VARCHAR(6) DEFAULT NULL,
          dictionary_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX uniq_389b783af5e5b3c5e237e06 ON tag (dictionary_id, name)');
        $this->addSql('CREATE INDEX idx_389b783af5e5b3c ON tag (dictionary_id)');
        $this->addSql('ALTER TABLE
          tag
        ADD
          CONSTRAINT fk_389b783af5e5b3c FOREIGN KEY (dictionary_id) REFERENCES dictionary (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('CREATE TABLE "user" (
          id UUID NOT NULL,
          email VARCHAR(180) NOT NULL,
          roles JSON NOT NULL,
          name VARCHAR(255) NOT NULL,
          avatar_url VARCHAR(255) DEFAULT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON "user" (email)');

        $this->addSql('CREATE TABLE french_entry (
          id UUID NOT NULL,
          value VARCHAR(255) NOT NULL,
          dictionary_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX uniq_4f7d0422af5e5b3c1d775834 ON french_entry (dictionary_id, value)');
        $this->addSql('CREATE INDEX idx_4f7d0422af5e5b3c ON french_entry (dictionary_id)');
        $this->addSql('ALTER TABLE
          french_entry
        ADD
          CONSTRAINT fk_4f7d0422af5e5b3c FOREIGN KEY (dictionary_id) REFERENCES dictionary (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('CREATE TABLE japanese_french_association (
          id UUID NOT NULL,
          japanese_entry_id UUID NOT NULL,
          french_entry_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX uniq_ddc487a06e94ffb317509f74 ON japanese_french_association (
          japanese_entry_id, french_entry_id
        )');
        $this->addSql('CREATE INDEX idx_ddc487a017509f74 ON japanese_french_association (french_entry_id)');
        $this->addSql('CREATE INDEX idx_ddc487a06e94ffb3 ON japanese_french_association (japanese_entry_id)');
        $this->addSql('ALTER TABLE
          japanese_french_association
        ADD
          CONSTRAINT fk_ddc487a06e94ffb3 FOREIGN KEY (japanese_entry_id) REFERENCES japanese_entry (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          japanese_french_association
        ADD
          CONSTRAINT fk_ddc487a017509f74 FOREIGN KEY (french_entry_id) REFERENCES french_entry (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('CREATE TABLE japanese_entry_tag (
          id UUID NOT NULL,
          japanese_entry_id UUID NOT NULL,
          tag_id UUID NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX uniq_3a5ba9d96e94ffb3bad26311 ON japanese_entry_tag (japanese_entry_id, tag_id)');
        $this->addSql('CREATE INDEX idx_3a5ba9d9bad26311 ON japanese_entry_tag (tag_id)');
        $this->addSql('CREATE INDEX idx_3a5ba9d96e94ffb3 ON japanese_entry_tag (japanese_entry_id)');
        $this->addSql('ALTER TABLE
          japanese_entry_tag
        ADD
          CONSTRAINT fk_3a5ba9d96e94ffb3 FOREIGN KEY (japanese_entry_id) REFERENCES japanese_entry (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          japanese_entry_tag
        ADD
          CONSTRAINT fk_3a5ba9d9bad26311 FOREIGN KEY (tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE dictionary');
        $this->addSql('DROP TABLE japanese_entry');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE french_entry');
        $this->addSql('DROP TABLE japanese_french_association');
        $this->addSql('DROP TABLE japanese_entry_tag');
    }
}
