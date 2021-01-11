<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210111050302 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE status ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE status ADD CONSTRAINT FK_7B00651C166D1F9C FOREIGN KEY (project_id) REFERENCES ppbasic (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7B00651C166D1F9C ON status (project_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE status DROP FOREIGN KEY FK_7B00651C166D1F9C');
        $this->addSql('DROP INDEX UNIQ_7B00651C166D1F9C ON status');
        $this->addSql('ALTER TABLE status DROP project_id');
    }
}
