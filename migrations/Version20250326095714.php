<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250326095714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__l3_users AS SELECT id, pays_id, login, roles, password, firstname, lastname, birthdate FROM l3_users');
        $this->addSql('DROP TABLE l3_users');
        $this->addSql('CREATE TABLE l3_users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_pays INTEGER DEFAULT NULL, login VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, firstname VARCHAR(200) NOT NULL, lastname VARCHAR(200) NOT NULL, birthdate DATETIME DEFAULT NULL, CONSTRAINT FK_54943D84BFBF20AC FOREIGN KEY (id_pays) REFERENCES l3_pays (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO l3_users (id, id_pays, login, roles, password, firstname, lastname, birthdate) SELECT id, pays_id, login, roles, password, firstname, lastname, birthdate FROM __temp__l3_users');
        $this->addSql('DROP TABLE __temp__l3_users');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_LOGIN ON l3_users (login)');
        $this->addSql('CREATE INDEX IDX_54943D84BFBF20AC ON l3_users (id_pays)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__l3_users AS SELECT id, id_pays, login, roles, password, firstname, lastname, birthdate FROM l3_users');
        $this->addSql('DROP TABLE l3_users');
        $this->addSql('CREATE TABLE l3_users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pays_id INTEGER DEFAULT NULL, login VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, firstname VARCHAR(200) NOT NULL, lastname VARCHAR(200) NOT NULL, birthdate DATETIME DEFAULT NULL, CONSTRAINT FK_54943D84A6E44244 FOREIGN KEY (pays_id) REFERENCES l3_pays (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO l3_users (id, pays_id, login, roles, password, firstname, lastname, birthdate) SELECT id, id_pays, login, roles, password, firstname, lastname, birthdate FROM __temp__l3_users');
        $this->addSql('DROP TABLE __temp__l3_users');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_LOGIN ON l3_users (login)');
        $this->addSql('CREATE INDEX IDX_54943D84A6E44244 ON l3_users (pays_id)');
    }
}
