<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260730135613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note ADD coach_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE note ALTER seance_id DROP NOT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA143C105691 FOREIGN KEY (coach_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_CFBDFA143C105691 ON note (coach_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note DROP CONSTRAINT FK_CFBDFA143C105691');
        $this->addSql('DROP INDEX IDX_CFBDFA143C105691');
        $this->addSql('ALTER TABLE note DROP coach_id');
        $this->addSql('ALTER TABLE note ALTER seance_id SET NOT NULL');
    }
}
