<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210818214148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add material information';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE material ADD information LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE material DROP information');
    }
}
