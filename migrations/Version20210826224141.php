<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210826224141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add more soft deletions';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE loan CHANGE reservation_id reservation_id INT NOT NULL');
        $this->addSql('ALTER TABLE material DROP FOREIGN KEY FK_7CBE759564D218E');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE759564D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE loan ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD slug VARCHAR(100) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649989D9B62 ON user (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE loan CHANGE reservation_id reservation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE material DROP FOREIGN KEY FK_7CBE759564D218E');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE759564D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE user DROP deleted_at');
        $this->addSql('ALTER TABLE loan DROP deleted_at');
        $this->addSql('ALTER TABLE reservation DROP deleted_at');
        $this->addSql('DROP INDEX UNIQ_8D93D649989D9B62 ON user');
        $this->addSql('ALTER TABLE user DROP slug');
    }
}
