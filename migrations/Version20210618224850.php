<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210618224850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add unique constraints.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F1B251E5E237E06 ON item (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B7835E237E06 ON tag (name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_1F1B251E5E237E06 ON item');
        $this->addSql('DROP INDEX UNIQ_389B7835E237E06 ON tag');
    }
}
