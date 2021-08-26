<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210826215234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Location as an entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_5E9E89CB5E237E06 (name), INDEX IDX_5E9E89CBB03A8386 (created_by_id), INDEX IDX_5E9E89CB896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE material ADD location_id INT DEFAULT NULL, DROP location');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE759564D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('CREATE INDEX IDX_7CBE759564D218E ON material (location_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE material DROP FOREIGN KEY FK_7CBE759564D218E');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP INDEX IDX_7CBE759564D218E ON material');
        $this->addSql('ALTER TABLE material ADD location VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP location_id');
    }
}
