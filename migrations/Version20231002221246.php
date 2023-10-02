<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231002221246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit_membre (produit_id INT NOT NULL, membre_id INT NOT NULL, INDEX IDX_1E63013F347EFB (produit_id), INDEX IDX_1E630136A99F74A (membre_id), PRIMARY KEY(produit_id, membre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_membre ADD CONSTRAINT FK_1E63013F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_membre ADD CONSTRAINT FK_1E630136A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE membre CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE produit CHANGE photo photo VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_membre DROP FOREIGN KEY FK_1E63013F347EFB');
        $this->addSql('ALTER TABLE produit_membre DROP FOREIGN KEY FK_1E630136A99F74A');
        $this->addSql('DROP TABLE produit_membre');
        $this->addSql('ALTER TABLE membre CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE produit CHANGE photo photo VARCHAR(255) NOT NULL');
    }
}
