<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321102558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C8B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BDAFD8C8B03A8386 ON author (created_by_id)');
        $this->addSql('ALTER TABLE comment ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9474526CB03A8386 ON comment (created_by_id)');
        $this->addSql('ALTER TABLE editor ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE editor ADD CONSTRAINT FK_CCF1F1BAB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CCF1F1BAB03A8386 ON editor (created_by_id)');
        $this->addSql('ALTER TABLE user ADD last_login_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author DROP FOREIGN KEY FK_BDAFD8C8B03A8386');
        $this->addSql('DROP INDEX IDX_BDAFD8C8B03A8386 ON author');
        $this->addSql('ALTER TABLE author DROP created_by_id');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CB03A8386');
        $this->addSql('DROP INDEX IDX_9474526CB03A8386 ON comment');
        $this->addSql('ALTER TABLE comment DROP created_by_id');
        $this->addSql('ALTER TABLE editor DROP FOREIGN KEY FK_CCF1F1BAB03A8386');
        $this->addSql('DROP INDEX IDX_CCF1F1BAB03A8386 ON editor');
        $this->addSql('ALTER TABLE editor DROP created_by_id');
        $this->addSql('ALTER TABLE user DROP last_login_at');
    }
}
