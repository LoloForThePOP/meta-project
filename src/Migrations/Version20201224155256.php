<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201224155256 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE news ADD image2 VARCHAR(255) DEFAULT NULL, ADD image3 VARCHAR(255) DEFAULT NULL, ADD caption_image1 VARCHAR(1000) DEFAULT NULL, ADD caption_image2 VARCHAR(1000) DEFAULT NULL, ADD caption_image3 VARCHAR(1000) DEFAULT NULL, ADD caption_video1 VARCHAR(1000) DEFAULT NULL, ADD caption_video2 VARCHAR(1000) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE news DROP image2, DROP image3, DROP caption_image1, DROP caption_image2, DROP caption_image3, DROP caption_video1, DROP caption_video2');
    }
}
