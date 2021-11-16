<?php

declare(strict_types=1);

namespace FC\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211115002637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE board_members (id UUID NOT NULL, board_id UUID NOT NULL, user_id UUID NOT NULL, roles JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DBEFAF0E7EC5785 ON board_members (board_id)');
        $this->addSql('CREATE INDEX IDX_DBEFAF0A76ED395 ON board_members (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DBEFAF0E7EC5785A76ED395 ON board_members (board_id, user_id)');
        $this->addSql('COMMENT ON COLUMN board_members.id IS \'(DC2Type:member_id)\'');
        $this->addSql('COMMENT ON COLUMN board_members.board_id IS \'(DC2Type:board_id)\'');
        $this->addSql('COMMENT ON COLUMN board_members.user_id IS \'(DC2Type:user_id)\'');
        $this->addSql('COMMENT ON COLUMN board_members.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE boards (id UUID NOT NULL, owner_id UUID NOT NULL, name VARCHAR(150) NOT NULL, color VARCHAR(50) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F3EE4D137E3C61F9 ON boards (owner_id)');
        $this->addSql('COMMENT ON COLUMN boards.id IS \'(DC2Type:board_id)\'');
        $this->addSql('COMMENT ON COLUMN boards.owner_id IS \'(DC2Type:user_id)\'');
        $this->addSql('COMMENT ON COLUMN boards.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE board_members ADD CONSTRAINT FK_DBEFAF0E7EC5785 FOREIGN KEY (board_id) REFERENCES boards (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE board_members DROP CONSTRAINT FK_DBEFAF0E7EC5785');
        $this->addSql('DROP TABLE board_members');
        $this->addSql('DROP TABLE boards');
    }
}
