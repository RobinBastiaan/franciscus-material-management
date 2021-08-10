<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210810222912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add User entity and implement *-able doctrine extensions.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, age_group LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD slug VARCHAR(100) NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F1B251E989D9B62 ON item (slug)');
        $this->addSql('CREATE INDEX IDX_1F1B251EB03A8386 ON item (created_by_id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E896DBBDE ON item (updated_by_id)');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03FCDAEAAA');
        $this->addSql('DROP INDEX IDX_C5D30D03FCDAEAAA ON loan');
        $this->addSql('ALTER TABLE loan ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, CHANGE order_id_id order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D038D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_C5D30D038D9F6D38 ON loan (order_id)');
        $this->addSql('ALTER TABLE note ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14B03A8386 ON note (created_by_id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14896DBBDE ON note (updated_by_id)');
        $this->addSql('ALTER TABLE `order` ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, CHANGE game_branch age_group VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F5299398B03A8386 ON `order` (created_by_id)');
        $this->addSql('CREATE INDEX IDX_F5299398896DBBDE ON `order` (updated_by_id)');
        $this->addSql('ALTER TABLE tag ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE item CHANGE description description LONGTEXT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495E237E06 ON user (name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EB03A8386');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E896DBBDE');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14B03A8386');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14896DBBDE');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398B03A8386');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398896DBBDE');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX UNIQ_1F1B251E989D9B62 ON item');
        $this->addSql('DROP INDEX IDX_1F1B251EB03A8386 ON item');
        $this->addSql('DROP INDEX IDX_1F1B251E896DBBDE ON item');
        $this->addSql('ALTER TABLE item DROP created_by_id, DROP updated_by_id, DROP slug, DROP created_at, DROP updated_at, DROP deleted_at');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D038D9F6D38');
        $this->addSql('DROP INDEX IDX_C5D30D038D9F6D38 ON loan');
        $this->addSql('ALTER TABLE loan DROP created_at, DROP updated_at, CHANGE order_id order_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_C5D30D03FCDAEAAA ON loan (order_id_id)');
        $this->addSql('DROP INDEX IDX_CFBDFA14B03A8386 ON note');
        $this->addSql('DROP INDEX IDX_CFBDFA14896DBBDE ON note');
        $this->addSql('ALTER TABLE note DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at, DROP deleted_at');
        $this->addSql('DROP INDEX IDX_F5299398B03A8386 ON `order`');
        $this->addSql('DROP INDEX IDX_F5299398896DBBDE ON `order`');
        $this->addSql('ALTER TABLE `order` DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at, CHANGE age_group game_branch VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tag DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE item CHANGE description description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX UNIQ_8D93D6495E237E06 ON user');
    }
}
