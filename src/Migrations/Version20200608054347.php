<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200608054347 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE geo_domain ADD administrative_area_level1 VARCHAR(255) DEFAULT NULL, ADD administrative_area_level2 VARCHAR(255) DEFAULT NULL, ADD route VARCHAR(255) DEFAULT NULL, ADD latitude VARCHAR(255) DEFAULT NULL, ADD longitude VARCHAR(255) DEFAULT NULL, DROP department_code, CHANGE department_name geo_type VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE geo_domain ADD department_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD department_code VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP geo_type, DROP administrative_area_level1, DROP administrative_area_level2, DROP route, DROP latitude, DROP longitude');
    }
}
