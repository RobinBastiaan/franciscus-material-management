<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210814152932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename Item to Material';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE item_tag DROP FOREIGN KEY FK_E49CCCB1126F525E');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03DA018627');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14126F525E');
        $this->addSql('CREATE TABLE material (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, amount INT DEFAULT 1 NOT NULL, status VARCHAR(255) DEFAULT \'Goed\' NOT NULL, date_bought DATETIME NOT NULL, value DOUBLE PRECISION DEFAULT NULL, depreciation_years INT DEFAULT NULL, manufacturer VARCHAR(255) DEFAULT NULL, location VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_7CBE75955E237E06 (name), UNIQUE INDEX UNIQ_7CBE7595989D9B62 (slug), INDEX IDX_7CBE7595B03A8386 (created_by_id), INDEX IDX_7CBE7595896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material_tag (material_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_B5B3AB75E308AC6F (material_id), INDEX IDX_B5B3AB75BAD26311 (tag_id), PRIMARY KEY(material_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE7595B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE7595896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE material_tag ADD CONSTRAINT FK_B5B3AB75E308AC6F FOREIGN KEY (material_id) REFERENCES material (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE material_tag ADD CONSTRAINT FK_B5B3AB75BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_tag');
        $this->addSql('DROP INDEX IDX_C5D30D03DA018627 ON loan');
        $this->addSql('ALTER TABLE loan CHANGE loaned_item_id loaned_material_id INT NOT NULL');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03117ED23E FOREIGN KEY (loaned_material_id) REFERENCES material (id)');
        $this->addSql('CREATE INDEX IDX_C5D30D03117ED23E ON loan (loaned_material_id)');
        $this->addSql('DROP INDEX IDX_CFBDFA14126F525E ON note');
        $this->addSql('ALTER TABLE note CHANGE item_id material_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14E308AC6F FOREIGN KEY (material_id) REFERENCES material (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14E308AC6F ON note (material_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03117ED23E');
        $this->addSql('ALTER TABLE material_tag DROP FOREIGN KEY FK_B5B3AB75E308AC6F');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14E308AC6F');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, amount INT DEFAULT 1 NOT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'Goed\' NOT NULL COLLATE `utf8mb4_unicode_ci`, date_bought DATETIME NOT NULL, value DOUBLE PRECISION DEFAULT NULL, depreciation DOUBLE PRECISION DEFAULT NULL, manufacturer VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, location VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, slug VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_1F1B251EB03A8386 (created_by_id), UNIQUE INDEX UNIQ_1F1B251E5E237E06 (name), INDEX IDX_1F1B251E896DBBDE (updated_by_id), UNIQUE INDEX UNIQ_1F1B251E989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE item_tag (item_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_E49CCCB1BAD26311 (tag_id), INDEX IDX_E49CCCB1126F525E (item_id), PRIMARY KEY(item_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE item_tag ADD CONSTRAINT FK_E49CCCB1126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_tag ADD CONSTRAINT FK_E49CCCB1BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE material_tag');
        $this->addSql('DROP INDEX IDX_C5D30D03117ED23E ON loan');
        $this->addSql('ALTER TABLE loan CHANGE loaned_material_id loaned_item_id INT NOT NULL');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03DA018627 FOREIGN KEY (loaned_item_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_C5D30D03DA018627 ON loan (loaned_item_id)');
        $this->addSql('DROP INDEX IDX_CFBDFA14E308AC6F ON note');
        $this->addSql('ALTER TABLE note CHANGE material_id item_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14126F525E ON note (item_id)');
    }
}
