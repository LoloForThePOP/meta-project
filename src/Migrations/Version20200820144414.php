<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200820144414 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE external_contributors_structure (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, rich_text_content LONGTEXT DEFAULT NULL, position SMALLINT DEFAULT NULL, INDEX IDX_D9204514166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE external_contributors_structure_persorg (external_contributors_structure_id INT NOT NULL, persorg_id INT NOT NULL, INDEX IDX_4732D29FCB0E55CD (external_contributors_structure_id), INDEX IDX_4732D29F7583A8E6 (persorg_id), PRIMARY KEY(external_contributors_structure_id, persorg_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persorg (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(1000) DEFAULT NULL, missions VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, webdomain1 VARCHAR(255) DEFAULT NULL, webdomain2 VARCHAR(255) DEFAULT NULL, webdomain3 VARCHAR(255) DEFAULT NULL, webdomain4 VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, position SMALLINT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE external_contributors_structure ADD CONSTRAINT FK_D9204514166D1F9C FOREIGN KEY (project_id) REFERENCES ppbasic (id)');
        $this->addSql('ALTER TABLE external_contributors_structure_persorg ADD CONSTRAINT FK_4732D29FCB0E55CD FOREIGN KEY (external_contributors_structure_id) REFERENCES external_contributors_structure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE external_contributors_structure_persorg ADD CONSTRAINT FK_4732D29F7583A8E6 FOREIGN KEY (persorg_id) REFERENCES persorg (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE external_contributors_structure_persorg DROP FOREIGN KEY FK_4732D29FCB0E55CD');
        $this->addSql('ALTER TABLE external_contributors_structure_persorg DROP FOREIGN KEY FK_4732D29F7583A8E6');
        $this->addSql('DROP TABLE external_contributors_structure');
        $this->addSql('DROP TABLE external_contributors_structure_persorg');
        $this->addSql('DROP TABLE persorg');
    }
}
