<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210103193125 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_follows DROP INDEX UNIQ_136E9479A76ED395, ADD INDEX IDX_136E9479A76ED395 (user_id)');
        $this->addSql('ALTER TABLE user_follows DROP INDEX UNIQ_136E9479AB627E8B, ADD INDEX IDX_136E9479AB627E8B (presentation_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_follows DROP INDEX IDX_136E9479A76ED395, ADD UNIQUE INDEX UNIQ_136E9479A76ED395 (user_id)');
        $this->addSql('ALTER TABLE user_follows DROP INDEX IDX_136E9479AB627E8B, ADD UNIQUE INDEX UNIQ_136E9479AB627E8B (presentation_id)');
    }
}
