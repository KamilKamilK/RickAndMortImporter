<?php

declare(strict_types=1);

namespace Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115173520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE characters (id SERIAL NOT NULL, location_id INT DEFAULT NULL, origin_id INT DEFAULT NULL, api_id INT NOT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(100) NOT NULL, species VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, gender VARCHAR(50) NOT NULL, image TEXT NOT NULL, url TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3A29410E64D218E ON characters (location_id)');
        $this->addSql('CREATE INDEX IDX_3A29410E56A273CC ON characters (origin_id)');
        $this->addSql('COMMENT ON COLUMN characters.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE character_episodes (character_id INT NOT NULL, episode_id INT NOT NULL, PRIMARY KEY(character_id, episode_id))');
        $this->addSql('CREATE INDEX IDX_25D4B74C1136BE75 ON character_episodes (character_id)');
        $this->addSql('CREATE INDEX IDX_25D4B74C362B62A0 ON character_episodes (episode_id)');
        $this->addSql('CREATE TABLE episodes (id SERIAL NOT NULL, api_id INT NOT NULL, name VARCHAR(255) NOT NULL, air_date VARCHAR(100) NOT NULL, episode VARCHAR(10) NOT NULL, url VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN episodes.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE locations (id SERIAL NOT NULL, api_id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, dimension VARCHAR(255) DEFAULT NULL, url VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN locations.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E64D218E FOREIGN KEY (location_id) REFERENCES locations (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E56A273CC FOREIGN KEY (origin_id) REFERENCES locations (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_episodes ADD CONSTRAINT FK_25D4B74C1136BE75 FOREIGN KEY (character_id) REFERENCES characters (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_episodes ADD CONSTRAINT FK_25D4B74C362B62A0 FOREIGN KEY (episode_id) REFERENCES episodes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE characters DROP CONSTRAINT FK_3A29410E64D218E');
        $this->addSql('ALTER TABLE characters DROP CONSTRAINT FK_3A29410E56A273CC');
        $this->addSql('ALTER TABLE character_episodes DROP CONSTRAINT FK_25D4B74C1136BE75');
        $this->addSql('ALTER TABLE character_episodes DROP CONSTRAINT FK_25D4B74C362B62A0');
        $this->addSql('DROP TABLE characters');
        $this->addSql('DROP TABLE character_episodes');
        $this->addSql('DROP TABLE episodes');
        $this->addSql('DROP TABLE locations');
    }
}
