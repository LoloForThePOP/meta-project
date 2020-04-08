<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200327082056 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, icon VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_ppbasic (category_id INT NOT NULL, ppbasic_id INT NOT NULL, INDEX IDX_EBA5C0A412469DE2 (category_id), INDEX IDX_EBA5C0A4F368E030 (ppbasic_id), PRIMARY KEY(category_id, ppbasic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_ppbasic ADD CONSTRAINT FK_EBA5C0A412469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_ppbasic ADD CONSTRAINT FK_EBA5C0A4F368E030 FOREIGN KEY (ppbasic_id) REFERENCES ppbasic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ppbasic DROP categories');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category_ppbasic DROP FOREIGN KEY FK_EBA5C0A412469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_ppbasic');
        $this->addSql('ALTER TABLE ppbasic ADD categories VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
