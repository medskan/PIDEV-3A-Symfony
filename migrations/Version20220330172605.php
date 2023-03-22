<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220330172605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonne (id INT AUTO_INCREMENT NOT NULL, abonnement_id INT NOT NULL, nom_a VARCHAR(50) NOT NULL, prenom_a VARCHAR(50) NOT NULL, age_a INT NOT NULL, sexe_a VARCHAR(50) NOT NULL, email_a VARCHAR(50) NOT NULL, mdp_a VARCHAR(100) NOT NULL, tel_a INT NOT NULL, adresse_a VARCHAR(100) NOT NULL, message VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_76328BF0F1D74413 (abonnement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, type_a VARCHAR(255) NOT NULL, tarif_a DOUBLE PRECISION NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, description LONGTEXT NOT NULL, all_day TINYINT(1) NOT NULL, background_color VARCHAR(255) NOT NULL, border_color VARCHAR(7) NOT NULL, text_color VARCHAR(7) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom_c VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, publication_id INT NOT NULL, redacteur_com VARCHAR(60) NOT NULL, date_com DATETIME NOT NULL, text VARCHAR(1000) NOT NULL, image VARCHAR(255) DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_67F068BC38B217A7 (publication_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipement (id INT AUTO_INCREMENT NOT NULL, fournisseur_id INT NOT NULL, nom_e VARCHAR(60) NOT NULL, type_e VARCHAR(100) NOT NULL, marque VARCHAR(100) NOT NULL, gamme VARCHAR(100) NOT NULL, quantite INT NOT NULL, date_commande DATE NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_B8B4C6F3670C757F (fournisseur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, nom_f VARCHAR(60) NOT NULL, prenom_f VARCHAR(60) NOT NULL, tel_f INT NOT NULL, email_f VARCHAR(70) NOT NULL, adresse VARCHAR(60) NOT NULL, rib_f VARCHAR(60) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livraison (id INT AUTO_INCREMENT NOT NULL, num_l INT NOT NULL, nom_livreur VARCHAR(60) NOT NULL, prenom_livreur VARCHAR(60) NOT NULL, tel_livreur VARCHAR(20) NOT NULL, adresse_livraison VARCHAR(60) NOT NULL, date_livraison DATE DEFAULT NULL, date_arrive DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, numero_commande VARCHAR(255) NOT NULL, total VARCHAR(255) NOT NULL, id_c VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_detail (id INT AUTO_INCREMENT NOT NULL, id_produit INT DEFAULT NULL, quantity INT NOT NULL, prix VARCHAR(255) NOT NULL, INDEX IDX_ED896F46F7384557 (id_produit), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(60) NOT NULL, reclamation VARCHAR(60) NOT NULL, date_d DATE NOT NULL, date_f DATE NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personnel (id INT AUTO_INCREMENT NOT NULL, salle_id INT NOT NULL, nom_p VARCHAR(60) NOT NULL, prenom_p VARCHAR(60) NOT NULL, tel_p INT NOT NULL, email_p VARCHAR(100) NOT NULL, mdp VARCHAR(60) NOT NULL, salaire_p DOUBLE PRECISION NOT NULL, poste VARCHAR(60) NOT NULL, h_travail INT NOT NULL, h_absence INT NOT NULL, date_embauche DATE NOT NULL, INDEX IDX_A6BCF3DEDC304035 (salle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id_produit INT AUTO_INCREMENT NOT NULL, id INT DEFAULT NULL, nom_produit VARCHAR(100) NOT NULL, description VARCHAR(225) NOT NULL, quantite INT NOT NULL, prix_produit DOUBLE PRECISION NOT NULL, promotion DOUBLE PRECISION NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_29A5EC27BF396750 (id), PRIMARY KEY(id_produit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_like (id INT AUTO_INCREMENT NOT NULL, id_produit INT DEFAULT NULL, abonne_id INT DEFAULT NULL, INDEX IDX_85FB3D5CF7384557 (id_produit), INDEX IDX_85FB3D5CC325A696 (abonne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pub_like (id INT AUTO_INCREMENT NOT NULL, publication_id INT DEFAULT NULL, abonne_id INT DEFAULT NULL, INDEX IDX_57AD89138B217A7 (publication_id), INDEX IDX_57AD891C325A696 (abonne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication (id INT AUTO_INCREMENT NOT NULL, redacteur_pub VARCHAR(60) NOT NULL, date_pub DATETIME NOT NULL, contenu VARCHAR(2000) NOT NULL, image VARCHAR(255) DEFAULT NULL, note INT DEFAULT NULL, user_id INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, redacteur_rec VARCHAR(60) NOT NULL, date_rec DATETIME NOT NULL, contenu_rec VARCHAR(2000) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, nom_s VARCHAR(60) NOT NULL, adresse_s VARCHAR(60) NOT NULL, code_postal INT DEFAULT NULL, ville VARCHAR(60) NOT NULL, nombre_p INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, lattitude VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonne ADD CONSTRAINT FK_76328BF0F1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnement (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F3670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F46F7384557 FOREIGN KEY (id_produit) REFERENCES produit (id_produit) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permission ADD CONSTRAINT FK_E04992AABF396750 FOREIGN KEY (id) REFERENCES personnel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personnel ADD CONSTRAINT FK_A6BCF3DEDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BF396750 FOREIGN KEY (id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE produit_like ADD CONSTRAINT FK_85FB3D5CF7384557 FOREIGN KEY (id_produit) REFERENCES produit (id_produit) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_like ADD CONSTRAINT FK_85FB3D5CC325A696 FOREIGN KEY (abonne_id) REFERENCES abonne (id)');
        $this->addSql('ALTER TABLE pub_like ADD CONSTRAINT FK_57AD89138B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE pub_like ADD CONSTRAINT FK_57AD891C325A696 FOREIGN KEY (abonne_id) REFERENCES abonne (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_like DROP FOREIGN KEY FK_85FB3D5CC325A696');
        $this->addSql('ALTER TABLE pub_like DROP FOREIGN KEY FK_57AD891C325A696');
        $this->addSql('ALTER TABLE abonne DROP FOREIGN KEY FK_76328BF0F1D74413');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BF396750');
        $this->addSql('ALTER TABLE equipement DROP FOREIGN KEY FK_B8B4C6F3670C757F');
        $this->addSql('ALTER TABLE permission DROP FOREIGN KEY FK_E04992AABF396750');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F46F7384557');
        $this->addSql('ALTER TABLE produit_like DROP FOREIGN KEY FK_85FB3D5CF7384557');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC38B217A7');
        $this->addSql('ALTER TABLE pub_like DROP FOREIGN KEY FK_57AD89138B217A7');
        $this->addSql('ALTER TABLE personnel DROP FOREIGN KEY FK_A6BCF3DEDC304035');
        $this->addSql('DROP TABLE abonne');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE calendar');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE equipement');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_detail');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE personnel');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produit_like');
        $this->addSql('DROP TABLE pub_like');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE salle');
        $this->addSql('DROP TABLE user');
    }
}
