<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210829201441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add AgeGroup color';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE age_group ADD color VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE age_group DROP color');
    }
}
