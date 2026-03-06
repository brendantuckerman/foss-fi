<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260306064908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scenario ADD current_net_worth INT DEFAULT NULL');
        $this->addSql('ALTER TABLE scenario ADD super_guarantee NUMERIC(5, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE scenario ADD inflation_rate NUMERIC(10, 2) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scenario DROP current_net_worth');
        $this->addSql('ALTER TABLE scenario DROP super_guarantee');
        $this->addSql('ALTER TABLE scenario DROP inflation_rate');
    }
}
