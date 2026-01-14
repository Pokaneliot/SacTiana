<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260114190216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ref VARCHAR(50) NOT NULL, name VARCHAR(50) DEFAULT NULL, purchase_price NUMERIC(15, 2) NOT NULL, selling_price NUMERIC(15, 2) NOT NULL, created_at DATE NOT NULL, id_category INTEGER NOT NULL, CONSTRAINT FK_D34A04AD5697F554 FOREIGN KEY (id_category) REFERENCES product_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D34A04AD5697F554 ON product (id_category)');
        $this->addSql('CREATE TABLE product_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CDFC73565E237E06 ON product_category (name)');
        $this->addSql('CREATE TABLE product_stock (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, quantity INTEGER DEFAULT NULL, id_product INTEGER NOT NULL, CONSTRAINT FK_EA6A2D3CDD7ADDD FOREIGN KEY (id_product) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_EA6A2D3CDD7ADDD ON product_stock (id_product)');
        $this->addSql('CREATE TABLE product_update (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ref VARCHAR(50) DEFAULT NULL, name VARCHAR(50) DEFAULT NULL, purchase_price NUMERIC(15, 2) DEFAULT NULL, selling_price NUMERIC(15, 2) DEFAULT NULL, update_at DATE NOT NULL, id_product INTEGER NOT NULL, CONSTRAINT FK_FA5974DCDD7ADDD FOREIGN KEY (id_product) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_FA5974DCDD7ADDD ON product_update (id_product)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE product_stock');
        $this->addSql('DROP TABLE product_update');
    }
}
