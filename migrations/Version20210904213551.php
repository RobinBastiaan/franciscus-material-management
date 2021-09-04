<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210904213551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add material residual value';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE material ADD residual_value DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE material DROP residual_value');
    }
}
