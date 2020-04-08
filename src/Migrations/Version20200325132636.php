<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200325132636 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contact ADD email1 VARCHAR(255) DEFAULT NULL, ADD email2 VARCHAR(255) DEFAULT NULL, ADD email3 VARCHAR(255) DEFAULT NULL, ADD tel1 VARCHAR(255) DEFAULT NULL, ADD tel2 VARCHAR(255) DEFAULT NULL, ADD tel3 VARCHAR(255) DEFAULT NULL, ADD website1 VARCHAR(255) DEFAULT NULL, ADD website2 VARCHAR(255) DEFAULT NULL, ADD website3 VARCHAR(255) DEFAULT NULL, DROP emails, DROP telephones, DROP websites');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contact ADD emails VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD telephones VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD websites VARCHAR(700) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP email1, DROP email2, DROP email3, DROP tel1, DROP tel2, DROP tel3, DROP website1, DROP website2, DROP website3');
    }
}
