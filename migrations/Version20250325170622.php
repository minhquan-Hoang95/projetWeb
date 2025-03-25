<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250325170622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE l3_panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_user INTEGER NOT NULL, id_product INTEGER NOT NULL, desire_quantity INTEGER DEFAULT NULL, CONSTRAINT FK_178F078F6B3CA4B FOREIGN KEY (id_user) REFERENCES l3_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_178F078FDD7ADDD FOREIGN KEY (id_product) REFERENCES l3_products (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_178F078F6B3CA4B ON l3_panier (id_user)');
        $this->addSql('CREATE INDEX IDX_178F078FDD7ADDD ON l3_panier (id_product)');
        $this->addSql('DROP TABLE panier');
        $this->addSql('CREATE TEMPORARY TABLE __temp__l3_products AS SELECT id, libelle, unit_price, quantity_in_stock FROM l3_products');
        $this->addSql('DROP TABLE l3_products');
        $this->addSql('CREATE TABLE l3_products (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, unit_price DOUBLE PRECISION NOT NULL, quantity_in_stock INTEGER NOT NULL)');
        $this->addSql('INSERT INTO l3_products (id, libelle, unit_price, quantity_in_stock) SELECT id, libelle, unit_price, quantity_in_stock FROM __temp__l3_products');
        $this->addSql('DROP TABLE __temp__l3_products');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, desire_quantity INTEGER NOT NULL)');
        $this->addSql('DROP TABLE l3_panier');
        $this->addSql('CREATE TEMPORARY TABLE __temp__l3_products AS SELECT id, libelle, unit_price, quantity_in_stock FROM l3_products');
        $this->addSql('DROP TABLE l3_products');
        $this->addSql('CREATE TABLE l3_products (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, unit_price INTEGER NOT NULL, quantity_in_stock INTEGER NOT NULL)');
        $this->addSql('INSERT INTO l3_products (id, libelle, unit_price, quantity_in_stock) SELECT id, libelle, unit_price, quantity_in_stock FROM __temp__l3_products');
        $this->addSql('DROP TABLE __temp__l3_products');
    }
}
