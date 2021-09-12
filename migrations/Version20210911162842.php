<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210911162842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add material image and receipt field';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE material ADD image VARCHAR(255) NULL DEFAULT NULL');
        $this->addSql('ALTER TABLE material ADD receipt VARCHAR(255) NULL DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE material DROP image');
        $this->addSql('ALTER TABLE material DROP receipt');
    }
}
