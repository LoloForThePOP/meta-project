<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821084611 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE external_contributors_structure_persorg');
        $this->addSql('ALTER TABLE persorg ADD external_contributors_structure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE persorg ADD CONSTRAINT FK_5EF14EF3CB0E55CD FOREIGN KEY (external_contributors_structure_id) REFERENCES external_contributors_structure (id)');
        $this->addSql('CREATE INDEX IDX_5EF14EF3CB0E55CD ON persorg (external_contributors_structure_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE external_contributors_structure_persorg (external_contributors_structure_id INT NOT NULL, persorg_id INT NOT NULL, INDEX IDX_4732D29F7583A8E6 (persorg_id), INDEX IDX_4732D29FCB0E55CD (external_contributors_structure_id), PRIMARY KEY(external_contributors_structure_id, persorg_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE external_contributors_structure_persorg ADD CONSTRAINT FK_4732D29F7583A8E6 FOREIGN KEY (persorg_id) REFERENCES persorg (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE external_contributors_structure_persorg ADD CONSTRAINT FK_4732D29FCB0E55CD FOREIGN KEY (external_contributors_structure_id) REFERENCES external_contributors_structure (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE persorg DROP FOREIGN KEY FK_5EF14EF3CB0E55CD');
        $this->addSql('DROP INDEX IDX_5EF14EF3CB0E55CD ON persorg');
        $this->addSql('ALTER TABLE persorg DROP external_contributors_structure_id');
    }
}
