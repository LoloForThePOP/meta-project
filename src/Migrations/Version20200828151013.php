<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200828151013 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE owner');
        $this->addSql('ALTER TABLE persorg ADD own_project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE persorg ADD CONSTRAINT FK_5EF14EF313B7D9DA FOREIGN KEY (own_project_id) REFERENCES ppbasic (id)');
        $this->addSql('CREATE INDEX IDX_5EF14EF313B7D9DA ON persorg (own_project_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE owner (id INT AUTO_INCREMENT NOT NULL, presentation_id INT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(700) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, website VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_CF60E67CA76ED395 (user_id), INDEX IDX_CF60E67CAB627E8B (presentation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE owner ADD CONSTRAINT FK_CF60E67CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE owner ADD CONSTRAINT FK_CF60E67CAB627E8B FOREIGN KEY (presentation_id) REFERENCES ppbasic (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE persorg DROP FOREIGN KEY FK_5EF14EF313B7D9DA');
        $this->addSql('DROP INDEX IDX_5EF14EF313B7D9DA ON persorg');
        $this->addSql('ALTER TABLE persorg DROP own_project_id');
    }
}
