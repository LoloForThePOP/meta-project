<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201211210822 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE geo_domain DROP administrative_area_level1, DROP administrative_area_level2');
        $this->addSql('ALTER TABLE ppbasic DROP FOREIGN KEY FK_2C7D80DA61220EA6');
        $this->addSql('ALTER TABLE ppbasic CHANGE creator_id creator_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ppbasic ADD CONSTRAINT FK_2C7D80DA61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE geo_domain ADD administrative_area_level1 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD administrative_area_level2 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ppbasic DROP FOREIGN KEY FK_2C7D80DA61220EA6');
        $this->addSql('ALTER TABLE ppbasic CHANGE creator_id creator_id INT NOT NULL');
        $this->addSql('ALTER TABLE ppbasic ADD CONSTRAINT FK_2C7D80DA61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
