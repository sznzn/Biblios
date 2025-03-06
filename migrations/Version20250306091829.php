<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306091829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP INDEX UNIQ_9474526C16A2B381, ADD INDEX IDX_9474526C16A2B381 (book_id)');
        $this->addSql('ALTER TABLE comment CHANGE book_id book_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP INDEX IDX_9474526C16A2B381, ADD UNIQUE INDEX UNIQ_9474526C16A2B381 (book_id)');
        $this->addSql('ALTER TABLE comment CHANGE book_id book_id INT DEFAULT NULL');
    }
}
