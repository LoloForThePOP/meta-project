<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200408102756 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE city_ppbasic DROP FOREIGN KEY FK_91353E888BAC62AF');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE city_ppbasic');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, postal_code SMALLINT DEFAULT NULL, city_name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE city_ppbasic (city_id INT NOT NULL, ppbasic_id INT NOT NULL, INDEX IDX_91353E888BAC62AF (city_id), INDEX IDX_91353E88F368E030 (ppbasic_id), PRIMARY KEY(city_id, ppbasic_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE city_ppbasic ADD CONSTRAINT FK_91353E888BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE city_ppbasic ADD CONSTRAINT FK_91353E88F368E030 FOREIGN KEY (ppbasic_id) REFERENCES ppbasic (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
