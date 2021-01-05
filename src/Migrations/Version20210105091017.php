<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210105091017 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ppmajor_logs (id INT AUTO_INCREMENT NOT NULL, presentation_id INT NOT NULL, logs LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_DF0B62A0AB627E8B (presentation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ppmajor_logs ADD CONSTRAINT FK_DF0B62A0AB627E8B FOREIGN KEY (presentation_id) REFERENCES ppbasic (id)');
        $this->addSql('DROP TABLE presentation_major_logs');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE presentation_major_logs (id INT AUTO_INCREMENT NOT NULL, presentation_id INT DEFAULT NULL, major_logs LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_C724659FAB627E8B (presentation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE presentation_major_logs ADD CONSTRAINT FK_C724659FAB627E8B FOREIGN KEY (presentation_id) REFERENCES ppbasic (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE ppmajor_logs');
    }
}
