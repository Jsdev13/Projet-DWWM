<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260805134913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute le nom de fichier de l\'image du cours sur la table seance';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE seance ADD image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE seance DROP image');
    }

}
