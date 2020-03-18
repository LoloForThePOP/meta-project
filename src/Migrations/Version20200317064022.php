<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200317064022 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ppbasic DROP FOREIGN KEY FK_2C7D80DAF675F31B');
        $this->addSql('DROP INDEX IDX_2C7D80DAF675F31B ON ppbasic');
        $this->addSql('ALTER TABLE ppbasic CHANGE author_id creator_id INT NOT NULL');
        $this->addSql('ALTER TABLE ppbasic ADD CONSTRAINT FK_2C7D80DA61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2C7D80DA61220EA6 ON ppbasic (creator_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ppbasic DROP FOREIGN KEY FK_2C7D80DA61220EA6');
        $this->addSql('DROP INDEX IDX_2C7D80DA61220EA6 ON ppbasic');
        $this->addSql('ALTER TABLE ppbasic CHANGE creator_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE ppbasic ADD CONSTRAINT FK_2C7D80DAF675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_2C7D80DAF675F31B ON ppbasic (author_id)');
    }
}
