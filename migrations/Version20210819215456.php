<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210819215456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Made Note.material mandatory';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE note CHANGE material_id material_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE note CHANGE material_id material_id INT DEFAULT NULL');
    }
}
