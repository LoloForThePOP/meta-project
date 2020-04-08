<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200329082127 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE geo_domain (id INT AUTO_INCREMENT NOT NULL, postal_code SMALLINT DEFAULT NULL, city VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE geo_domain_ppbasic (geo_domain_id INT NOT NULL, ppbasic_id INT NOT NULL, INDEX IDX_941E9BECB06024EC (geo_domain_id), INDEX IDX_941E9BECF368E030 (ppbasic_id), PRIMARY KEY(geo_domain_id, ppbasic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE geo_domain_ppbasic ADD CONSTRAINT FK_941E9BECB06024EC FOREIGN KEY (geo_domain_id) REFERENCES geo_domain (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE geo_domain_ppbasic ADD CONSTRAINT FK_941E9BECF368E030 FOREIGN KEY (ppbasic_id) REFERENCES ppbasic (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE geo');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE geo_domain_ppbasic DROP FOREIGN KEY FK_941E9BECB06024EC');
        $this->addSql('CREATE TABLE geo (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, postal_codes VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_775EE79C166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE geo ADD CONSTRAINT FK_775EE79C166D1F9C FOREIGN KEY (project_id) REFERENCES ppbasic (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE geo_domain');
        $this->addSql('DROP TABLE geo_domain_ppbasic');
    }
}
