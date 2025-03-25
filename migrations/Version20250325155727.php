<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250325155727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE l3_products_pays (id_product INTEGER NOT NULL, id_pays INTEGER NOT NULL, PRIMARY KEY(id_product, id_pays), CONSTRAINT FK_696115FFDD7ADDD FOREIGN KEY (id_product) REFERENCES l3_products (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_696115FFBFBF20AC FOREIGN KEY (id_pays) REFERENCES l3_pays (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_696115FFDD7ADDD ON l3_products_pays (id_product)');
        $this->addSql('CREATE INDEX IDX_696115FFBFBF20AC ON l3_products_pays (id_pays)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE l3_products_pays');
    }
}
