<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260511145157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

  public function up(Schema $schema): void
{
    // Colonnes déjà existantes en base, migration vidée pour éviter les conflits
}

public function down(Schema $schema): void
{
    $this->addSql('ALTER TABLE enseignant DROP FOREIGN KEY FK_81A72FA1A76ED395');
    $this->addSql('DROP INDEX UNIQ_81A72FA1A76ED395 ON enseignant');
    $this->addSql('ALTER TABLE enseignant DROP user_id');
    $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3A76ED395');
    $this->addSql('DROP INDEX UNIQ_717E22E3A76ED395 ON etudiant');
    $this->addSql('ALTER TABLE etudiant DROP user_id');
}
}
