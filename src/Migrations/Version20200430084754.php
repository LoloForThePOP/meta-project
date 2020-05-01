<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200430084754 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pgroup (id INT AUTO_INCREMENT NOT NULL, creator_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(600) DEFAULT NULL, keywords VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_11021FBA61220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pgroup_user (pgroup_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D2E606508DDCD155 (pgroup_id), INDEX IDX_D2E60650A76ED395 (user_id), PRIMARY KEY(pgroup_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE included_projects (pgroup_id INT NOT NULL, ppbasic_id INT NOT NULL, INDEX IDX_D64E8C268DDCD155 (pgroup_id), INDEX IDX_D64E8C26F368E030 (ppbasic_id), PRIMARY KEY(pgroup_id, ppbasic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidates_projects (pgroup_id INT NOT NULL, ppbasic_id INT NOT NULL, INDEX IDX_3849C8B68DDCD155 (pgroup_id), INDEX IDX_3849C8B6F368E030 (ppbasic_id), PRIMARY KEY(pgroup_id, ppbasic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invited_projects (pgroup_id INT NOT NULL, ppbasic_id INT NOT NULL, INDEX IDX_769E7B4E8DDCD155 (pgroup_id), INDEX IDX_769E7B4EF368E030 (ppbasic_id), PRIMARY KEY(pgroup_id, ppbasic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pgroup ADD CONSTRAINT FK_11021FBA61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pgroup_user ADD CONSTRAINT FK_D2E606508DDCD155 FOREIGN KEY (pgroup_id) REFERENCES pgroup (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pgroup_user ADD CONSTRAINT FK_D2E60650A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE included_projects ADD CONSTRAINT FK_D64E8C268DDCD155 FOREIGN KEY (pgroup_id) REFERENCES pgroup (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE included_projects ADD CONSTRAINT FK_D64E8C26F368E030 FOREIGN KEY (ppbasic_id) REFERENCES ppbasic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidates_projects ADD CONSTRAINT FK_3849C8B68DDCD155 FOREIGN KEY (pgroup_id) REFERENCES pgroup (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidates_projects ADD CONSTRAINT FK_3849C8B6F368E030 FOREIGN KEY (ppbasic_id) REFERENCES ppbasic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE invited_projects ADD CONSTRAINT FK_769E7B4E8DDCD155 FOREIGN KEY (pgroup_id) REFERENCES pgroup (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE invited_projects ADD CONSTRAINT FK_769E7B4EF368E030 FOREIGN KEY (ppbasic_id) REFERENCES ppbasic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE geo_domain DROP type');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pgroup_user DROP FOREIGN KEY FK_D2E606508DDCD155');
        $this->addSql('ALTER TABLE included_projects DROP FOREIGN KEY FK_D64E8C268DDCD155');
        $this->addSql('ALTER TABLE candidates_projects DROP FOREIGN KEY FK_3849C8B68DDCD155');
        $this->addSql('ALTER TABLE invited_projects DROP FOREIGN KEY FK_769E7B4E8DDCD155');
        $this->addSql('DROP TABLE pgroup');
        $this->addSql('DROP TABLE pgroup_user');
        $this->addSql('DROP TABLE included_projects');
        $this->addSql('DROP TABLE candidates_projects');
        $this->addSql('DROP TABLE invited_projects');
        $this->addSql('ALTER TABLE geo_domain ADD type VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
