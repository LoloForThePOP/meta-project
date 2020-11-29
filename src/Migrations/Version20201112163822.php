<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201112163822 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE teammate ADD persorg_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE teammate ADD CONSTRAINT FK_C06EEBAE7583A8E6 FOREIGN KEY (persorg_id) REFERENCES persorg (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C06EEBAE7583A8E6 ON teammate (persorg_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE teammate DROP FOREIGN KEY FK_C06EEBAE7583A8E6');
        $this->addSql('DROP INDEX UNIQ_C06EEBAE7583A8E6 ON teammate');
        $this->addSql('ALTER TABLE teammate DROP persorg_id');
    }
}
