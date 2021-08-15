<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210815181624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Blameable fields to Loan and Tag';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE loan ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C5D30D03B03A8386 ON loan (created_by_id)');
        $this->addSql('CREATE INDEX IDX_C5D30D03896DBBDE ON loan (updated_by_id)');
        $this->addSql('ALTER TABLE tag ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_389B783B03A8386 ON tag (created_by_id)');
        $this->addSql('CREATE INDEX IDX_389B783896DBBDE ON tag (updated_by_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03B03A8386');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03896DBBDE');
        $this->addSql('DROP INDEX IDX_C5D30D03B03A8386 ON loan');
        $this->addSql('DROP INDEX IDX_C5D30D03896DBBDE ON loan');
        $this->addSql('ALTER TABLE loan DROP created_by_id, DROP updated_by_id');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B783B03A8386');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B783896DBBDE');
        $this->addSql('DROP INDEX IDX_389B783B03A8386 ON tag');
        $this->addSql('DROP INDEX IDX_389B783896DBBDE ON tag');
        $this->addSql('ALTER TABLE tag DROP created_by_id, DROP updated_by_id');
    }
}
