<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210828215859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add AgeGroup as an entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE age_group (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_F88B42535E237E06 (name), INDEX IDX_F88B4253B03A8386 (created_by_id), INDEX IDX_F88B4253896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE age_group_user (age_group_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_AF1D1C2BB09E220E (age_group_id), INDEX IDX_AF1D1C2BA76ED395 (user_id), PRIMARY KEY(age_group_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE age_group ADD CONSTRAINT FK_F88B4253B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE age_group ADD CONSTRAINT FK_F88B4253896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE age_group_user ADD CONSTRAINT FK_AF1D1C2BB09E220E FOREIGN KEY (age_group_id) REFERENCES age_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE age_group_user ADD CONSTRAINT FK_AF1D1C2BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD age_group_id INT DEFAULT NULL, DROP age_group');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955B09E220E FOREIGN KEY (age_group_id) REFERENCES age_group (id)');
        $this->addSql('CREATE INDEX IDX_42C84955B09E220E ON reservation (age_group_id)');
        $this->addSql('ALTER TABLE user DROP age_group');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE age_group_user DROP FOREIGN KEY FK_AF1D1C2BB09E220E');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955B09E220E');
        $this->addSql('DROP TABLE age_group');
        $this->addSql('DROP TABLE age_group_user');
        $this->addSql('DROP INDEX IDX_42C84955B09E220E ON reservation');
        $this->addSql('ALTER TABLE reservation ADD age_group VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP age_group_id');
        $this->addSql('ALTER TABLE user ADD age_group LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
    }
}
