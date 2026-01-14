<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260106104712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création des tables métier (restaurant) sans messenger_messages';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE article_commande (
            id INT AUTO_INCREMENT NOT NULL,
            quantite INT NOT NULL,
            prix_unitaire DOUBLE PRECISION NOT NULL,
            prix_total DOUBLE PRECISION NOT NULL,
            id_commande INT NOT NULL,
            id_article INT NOT NULL,
            INDEX IDX_3B0252163E314AE8 (id_commande),
            INDEX IDX_3B025216DCA7A716 (id_article),
            PRIMARY KEY (id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');

        $this->addSql('CREATE TABLE article_menu (
            id INT AUTO_INCREMENT NOT NULL,
            nom_article VARCHAR(255) NOT NULL,
            description LONGTEXT DEFAULT NULL,
            prix DOUBLE PRECISION NOT NULL,
            categorie VARCHAR(255) NOT NULL,
            disponible TINYINT NOT NULL,
            menu_id INT NOT NULL,
            INDEX IDX_CD47BD92CCD7E912 (menu_id),
            PRIMARY KEY (id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');

        $this->addSql('CREATE TABLE commande (
            id INT AUTO_INCREMENT NOT NULL,
            date_heure DATETIME NOT NULL,
            statut VARCHAR(255) NOT NULL,
            montant_total DOUBLE PRECISION NOT NULL,
            notes_speciales LONGTEXT DEFAULT NULL,
            id_table INT DEFAULT NULL,
            id_personnel INT DEFAULT NULL,
            INDEX IDX_6EEAA67D18ACCE76 (id_table),
            INDEX IDX_6EEAA67D26894FF9 (id_personnel),
            PRIMARY KEY (id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');

        $this->addSql('CREATE TABLE menu (
            id INT AUTO_INCREMENT NOT NULL,
            nom_menu VARCHAR(255) NOT NULL,
            description LONGTEXT DEFAULT NULL,
            prix_base DOUBLE PRECISION NOT NULL,
            PRIMARY KEY (id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');

        $this->addSql('CREATE TABLE personnel (
            id INT AUTO_INCREMENT NOT NULL,
            nom VARCHAR(255) NOT NULL,
            roles JSON NOT NULL,
            login VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            UNIQUE INDEX UNIQ_A6BCF3DEAA08CB10 (login),
            PRIMARY KEY (id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');

        $this->addSql('CREATE TABLE reservation (
            id INT AUTO_INCREMENT NOT NULL,
            date_heure DATETIME NOT NULL,
            nom_client VARCHAR(255) NOT NULL,
            nombre_personnes INT NOT NULL,
            telephone VARCHAR(255) NOT NULL,
            notes LONGTEXT DEFAULT NULL,
            id_table INT DEFAULT NULL,
            INDEX IDX_42C8495518ACCE76 (id_table),
            PRIMARY KEY (id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');

        $this->addSql('CREATE TABLE suivi_cuisine (
            id INT AUTO_INCREMENT NOT NULL,
            etape VARCHAR(255) NOT NULL,
            temps_estime INT DEFAULT NULL,
            priorite VARCHAR(255) NOT NULL,
            id_commande INT NOT NULL,
            UNIQUE INDEX UNIQ_9512D8CE3E314AE8 (id_commande),
            PRIMARY KEY (id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');

        $this->addSql('CREATE TABLE `table` (
            id INT AUTO_INCREMENT NOT NULL,
            numero_table INT NOT NULL,
            capacite INT NOT NULL,
            emplacement VARCHAR(255) NOT NULL,
            statut VARCHAR(255) NOT NULL,
            PRIMARY KEY (id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');

        // Foreign keys
        $this->addSql('ALTER TABLE article_commande ADD CONSTRAINT FK_3B0252163E314AE8 FOREIGN KEY (id_commande) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE article_commande ADD CONSTRAINT FK_3B025216DCA7A716 FOREIGN KEY (id_article) REFERENCES article_menu (id)');
        $this->addSql('ALTER TABLE article_menu ADD CONSTRAINT FK_CD47BD92CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D18ACCE76 FOREIGN KEY (id_table) REFERENCES `table` (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D26894FF9 FOREIGN KEY (id_personnel) REFERENCES personnel (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495518ACCE76 FOREIGN KEY (id_table) REFERENCES `table` (id)');
        $this->addSql('ALTER TABLE suivi_cuisine ADD CONSTRAINT FK_9512D8CE3E314AE8 FOREIGN KEY (id_commande) REFERENCES commande (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE article_commande DROP FOREIGN KEY FK_3B0252163E314AE8');
        $this->addSql('ALTER TABLE article_commande DROP FOREIGN KEY FK_3B025216DCA7A716');
        $this->addSql('ALTER TABLE article_menu DROP FOREIGN KEY FK_CD47BD92CCD7E912');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D18ACCE76');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D26894FF9');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495518ACCE76');
        $this->addSql('ALTER TABLE suivi_cuisine DROP FOREIGN KEY FK_9512D8CE3E314AE8');

        $this->addSql('DROP TABLE article_commande');
        $this->addSql('DROP TABLE article_menu');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE personnel');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE suivi_cuisine');
        $this->addSql('DROP TABLE `table`');
    }
}
