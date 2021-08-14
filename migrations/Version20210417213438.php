<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210417213438 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Initial database setup';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, amount INT NOT NULL, status VARCHAR(255) NOT NULL, date_bought DATETIME NOT NULL, value DOUBLE PRECISION NOT NULL, depreciation DOUBLE PRECISION DEFAULT NULL, manufacturer VARCHAR(255) DEFAULT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE loan (id INT AUTO_INCREMENT NOT NULL, loaned_item_id INT NOT NULL, order_id_id INT DEFAULT NULL, date_returned DATE DEFAULT NULL, returned_status VARCHAR(255) DEFAULT NULL, INDEX IDX_C5D30D03DA018627 (loaned_item_id), INDEX IDX_C5D30D03FCDAEAAA (order_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, game_branch VARCHAR(255) NOT NULL, date_start DATE NOT NULL, date_end DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03DA018627 FOREIGN KEY (loaned_item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03DA018627');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03FCDAEAAA');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE loan');
        $this->addSql('DROP TABLE `order`');
    }
}
