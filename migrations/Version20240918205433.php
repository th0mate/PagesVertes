<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240918205433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur ADD prenom VARCHAR(255) DEFAULT NULL, ADD nom VARCHAR(255) DEFAULT NULL, ADD telephone INT DEFAULT NULL, ADD facebook VARCHAR(255) DEFAULT NULL, CHANGE code code VARCHAR(255) NOT NULL, CHANGE est_visible est_visible TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_CODE ON utilisateur (code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_CODE ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP prenom, DROP nom, DROP telephone, DROP facebook, CHANGE code code INT NOT NULL, CHANGE est_visible est_visible TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
