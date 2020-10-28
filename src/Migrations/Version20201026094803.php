<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201026094803 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE persorg DROP FOREIGN KEY FK_5EF14EF313B7D9DA');
        $this->addSql('DROP INDEX IDX_5EF14EF313B7D9DA ON persorg');
        $this->addSql('ALTER TABLE persorg DROP own_project_id');
        $this->addSql('ALTER TABLE user ADD user_informations_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498284110D FOREIGN KEY (user_informations_id) REFERENCES persorg (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6498284110D ON user (user_informations_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE persorg ADD own_project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE persorg ADD CONSTRAINT FK_5EF14EF313B7D9DA FOREIGN KEY (own_project_id) REFERENCES ppbasic (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5EF14EF313B7D9DA ON persorg (own_project_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498284110D');
        $this->addSql('DROP INDEX UNIQ_8D93D6498284110D ON user');
        $this->addSql('ALTER TABLE user DROP user_informations_id');
    }
}
