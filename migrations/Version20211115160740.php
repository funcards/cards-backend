<?php

declare(strict_types=1);

namespace FC\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211115160740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id UUID NOT NULL, board_id UUID NOT NULL, name VARCHAR(150) NOT NULL, position INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3AF34668E7EC5785 ON categories (board_id)');
        $this->addSql('CREATE INDEX IDX_3AF34668E7EC5785462CE4F5 ON categories (board_id, position)');
        $this->addSql('COMMENT ON COLUMN categories.id IS \'(DC2Type:category_id)\'');
        $this->addSql('COMMENT ON COLUMN categories.board_id IS \'(DC2Type:board_id)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categories');
    }
}
