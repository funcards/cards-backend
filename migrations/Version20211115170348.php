<?php

declare(strict_types=1);

namespace FC\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211115170348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tags (id UUID NOT NULL, board_id UUID NOT NULL, name VARCHAR(100) NOT NULL, color VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6FBC9426E7EC5785 ON tags (board_id)');
        $this->addSql('COMMENT ON COLUMN tags.id IS \'(DC2Type:tag_id)\'');
        $this->addSql('COMMENT ON COLUMN tags.board_id IS \'(DC2Type:board_id)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tags');
    }
}
