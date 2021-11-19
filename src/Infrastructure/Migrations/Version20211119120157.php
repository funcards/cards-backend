<?php

declare(strict_types=1);

namespace FC\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211119120157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE categories (id UUID NOT NULL, board_id UUID NOT NULL, name VARCHAR(150) NOT NULL, "position" INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_3af34668e7ec5785 ON categories (board_id)');
        $this->addSql('CREATE INDEX idx_3af34668e7ec5785462ce4f5 ON categories (board_id, position)');
        $this->addSql('COMMENT ON COLUMN categories.id IS \'(DC2Type:category_id)\'');
        $this->addSql('COMMENT ON COLUMN categories.board_id IS \'(DC2Type:board_id)\'');

        $this->addSql('CREATE TABLE tags (id UUID NOT NULL, board_id UUID NOT NULL, name VARCHAR(100) NOT NULL, color VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_6fbc9426e7ec5785 ON tags (board_id)');
        $this->addSql('COMMENT ON COLUMN tags.id IS \'(DC2Type:tag_id)\'');
        $this->addSql('COMMENT ON COLUMN tags.board_id IS \'(DC2Type:board_id)\'');

        $this->addSql('CREATE TABLE users (id UUID NOT NULL, name VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, password TEXT NOT NULL, roles JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_1483a5e9e7927c74 ON users (email)');
        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:user_id)\'');
        $this->addSql('COMMENT ON COLUMN users.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.updated_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('CREATE TABLE boards (id UUID NOT NULL, owner_id UUID NOT NULL, name VARCHAR(150) NOT NULL, color VARCHAR(50) NOT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_f3ee4d137e3c61f9 ON boards (owner_id)');
        $this->addSql('COMMENT ON COLUMN boards.id IS \'(DC2Type:board_id)\'');
        $this->addSql('COMMENT ON COLUMN boards.owner_id IS \'(DC2Type:user_id)\'');
        $this->addSql('COMMENT ON COLUMN boards.created_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('CREATE TABLE board_members (id UUID NOT NULL, board_id UUID NOT NULL, user_id UUID NOT NULL, roles JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_dbefaf0a76ed395 ON board_members (user_id)');
        $this->addSql('CREATE INDEX idx_dbefaf0e7ec5785 ON board_members (board_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_dbefaf0e7ec5785a76ed395 ON board_members (board_id, user_id)');
        $this->addSql('COMMENT ON COLUMN board_members.id IS \'(DC2Type:member_id)\'');
        $this->addSql('COMMENT ON COLUMN board_members.board_id IS \'(DC2Type:board_id)\'');
        $this->addSql('COMMENT ON COLUMN board_members.user_id IS \'(DC2Type:user_id)\'');
        $this->addSql('COMMENT ON COLUMN board_members.created_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('CREATE TABLE cards (id UUID NOT NULL, board_id UUID NOT NULL, category_id UUID NOT NULL, name VARCHAR(1000) NOT NULL, content TEXT NOT NULL, tags JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_4c258fde7ec5785 ON cards (board_id)');
        $this->addSql('CREATE INDEX idx_4c258fde7ec578512469de2 ON cards (board_id, category_id)');
        $this->addSql('CREATE INDEX idx_4c258fd12469de2 ON cards (category_id)');
        $this->addSql('COMMENT ON COLUMN cards.id IS \'(DC2Type:card_id)\'');
        $this->addSql('COMMENT ON COLUMN cards.board_id IS \'(DC2Type:board_id)\'');
        $this->addSql('COMMENT ON COLUMN cards.category_id IS \'(DC2Type:category_id)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE cards');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE board_members');
        $this->addSql('DROP TABLE boards');
        $this->addSql('DROP TABLE users');
    }
}
