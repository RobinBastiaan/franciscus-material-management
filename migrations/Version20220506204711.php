<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220506204711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Category entity.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, icon VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_64C19C15E237E06 (name), INDEX IDX_64C19C1B03A8386 (created_by_id), INDEX IDX_64C19C1896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE material ADD category_id INT DEFAULT NULL, DROP category');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE759512469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_7CBE759512469DE2 ON material (category_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE material DROP FOREIGN KEY FK_7CBE759512469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP INDEX IDX_7CBE759512469DE2 ON material');
        $this->addSql('ALTER TABLE material ADD category VARCHAR(255) NOT NULL, DROP category_id');
    }
}
