<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190926201125 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pitch (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, sport VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slot (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', pitch_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', starts DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ends DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', price DOUBLE PRECISION NOT NULL, currency VARCHAR(3) NOT NULL, is_available TINYINT(1) NOT NULL, INDEX IDX_AC0E2067FEEFC64B (pitch_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E2067FEEFC64B FOREIGN KEY (pitch_id) REFERENCES pitch (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE slot DROP FOREIGN KEY FK_AC0E2067FEEFC64B');
        $this->addSql('DROP TABLE pitch');
        $this->addSql('DROP TABLE slot');
    }
}
