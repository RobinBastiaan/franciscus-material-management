<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210814173135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename status to state and add Note-Loan relation';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE loan CHANGE returned_status returned_state VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE material CHANGE status state VARCHAR(255) DEFAULT \'Goed\' NOT NULL');
        $this->addSql('ALTER TABLE note ADD loan_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14CE73868F FOREIGN KEY (loan_id) REFERENCES loan (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14CE73868F ON note (loan_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE loan CHANGE returned_state returned_status VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE material CHANGE state status VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'Goed\' NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14CE73868F');
        $this->addSql('DROP INDEX IDX_CFBDFA14CE73868F ON note');
        $this->addSql('ALTER TABLE note DROP loan_id');
    }
}
