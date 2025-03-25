<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250325171517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__l3_panier AS SELECT id, id_user, id_product, desire_quantity FROM l3_panier');
        $this->addSql('DROP TABLE l3_panier');
        $this->addSql('CREATE TABLE l3_panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_user INTEGER NOT NULL, id_product INTEGER NOT NULL, desire_quantity INTEGER DEFAULT NULL, CONSTRAINT FK_178F078F6B3CA4B FOREIGN KEY (id_user) REFERENCES l3_users (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_178F078FDD7ADDD FOREIGN KEY (id_product) REFERENCES l3_products (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO l3_panier (id, id_user, id_product, desire_quantity) SELECT id, id_user, id_product, desire_quantity FROM __temp__l3_panier');
        $this->addSql('DROP TABLE __temp__l3_panier');
        $this->addSql('CREATE INDEX IDX_178F078FDD7ADDD ON l3_panier (id_product)');
        $this->addSql('CREATE INDEX IDX_178F078F6B3CA4B ON l3_panier (id_user)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_USER_PRODUCT ON l3_panier (id_user, id_product)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__l3_panier AS SELECT id, id_user, id_product, desire_quantity FROM l3_panier');
        $this->addSql('DROP TABLE l3_panier');
        $this->addSql('CREATE TABLE l3_panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_user INTEGER NOT NULL, id_product INTEGER NOT NULL, desire_quantity INTEGER DEFAULT NULL, CONSTRAINT FK_178F078F6B3CA4B FOREIGN KEY (id_user) REFERENCES l3_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_178F078FDD7ADDD FOREIGN KEY (id_product) REFERENCES l3_products (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO l3_panier (id, id_user, id_product, desire_quantity) SELECT id, id_user, id_product, desire_quantity FROM __temp__l3_panier');
        $this->addSql('DROP TABLE __temp__l3_panier');
        $this->addSql('CREATE INDEX IDX_178F078F6B3CA4B ON l3_panier (id_user)');
        $this->addSql('CREATE INDEX IDX_178F078FDD7ADDD ON l3_panier (id_product)');
    }
}
