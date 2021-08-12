<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210812222000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Set default values on user properties.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item CHANGE amount amount INT DEFAULT 1 NOT NULL, CHANGE status status VARCHAR(255) DEFAULT \'Goed\' NOT NULL, CHANGE value value DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD slug VARCHAR(100) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_42C849555E237E06 ON reservation (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_42C84955989D9B62 ON reservation (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item CHANGE amount amount INT NOT NULL, CHANGE status status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE value value DOUBLE PRECISION NOT NULL');
        $this->addSql('DROP INDEX UNIQ_42C849555E237E06 ON reservation');
        $this->addSql('DROP INDEX UNIQ_42C84955989D9B62 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP slug');
    }
}
