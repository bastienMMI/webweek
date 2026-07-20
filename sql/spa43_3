-- ============================================================
--  SPA Haute-Loire — Sprint 2 : retour de la boutique
--  SAE301 (rattrapage)
--
--  La vente en ligne est interdite par le sujet. La boutique
--  revient donc sous forme de RÉSERVATION GRATUITE (retrait et
--  paiement au refuge), sur le même principe que la
--  pré-réservation des animaux.
--
--  À exécuter APRÈS spa43.sql + spa43_2.sql (migration cible).
-- ============================================================

-- ------------------------------------------------------------
-- 1. Table `produit` (catalogue de la boutique du refuge)
--    Reprise de l'ancienne structure, sans la notion de vente :
--    le prix reste affiché à titre indicatif (paiement sur place),
--    plus de table boutique / commande / commande_produit.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `produit` (
  `id_produit`        INT(11)       NOT NULL AUTO_INCREMENT,
  `nom_produit`       VARCHAR(100)  NOT NULL,
  `description`       TEXT          DEFAULT NULL,
  `prix`              DECIMAL(10,2) NOT NULL,
  `stock`             INT(11)       NOT NULL DEFAULT 0,
  `photo`             VARCHAR(255)  DEFAULT NULL,
  `actif`             TINYINT(1)    NOT NULL DEFAULT 1,
  `date_ajout`        TIMESTAMP     NOT NULL DEFAULT current_timestamp(),
  `date_modification` TIMESTAMP     NOT NULL DEFAULT current_timestamp()
                                    ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_produit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 2. Table `reservation_produit`
--    Miroir de la table `reservation` des animaux :
--    un utilisateur met de côté un produit, le refuge prépare,
--    le retrait (et le paiement) se font sur place.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `reservation_produit` (
  `id_reservation_produit` INT(11)   NOT NULL AUTO_INCREMENT,
  `id_utilisateur`         INT(11)   NOT NULL,
  `id_produit`             INT(11)   NOT NULL,
  `quantite`               INT(11)   NOT NULL DEFAULT 1,
  `date_reservation`       TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `statut`                 ENUM('en_attente','prete','retiree','annulee')
                           NOT NULL DEFAULT 'en_attente',
  PRIMARY KEY (`id_reservation_produit`),
  CONSTRAINT `fk_resaprod_user`
    FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  CONSTRAINT `fk_resaprod_produit`
    FOREIGN KEY (`id_produit`)     REFERENCES `produit` (`id_produit`)         ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 3. Catalogue : reprise des produits existants du refuge
-- ------------------------------------------------------------
INSERT INTO `produit` (`id_produit`, `nom_produit`, `description`, `prix`, `stock`, `photo`, `actif`) VALUES
(1,  'Shampoing solide',      'Soin naturel à l\'argile pour poils courts.',      12.50, 20, 'boutique/shampoing solide.webp', 1),
(2,  'Shampooing universel',  'Soin complet pour tous types de pelages.',          9.90, 15, 'boutique/shampoing universel.webp', 1),
(3,  'Shampooing Chat',       'Formule extra-douce respectant le PH.',            10.50, 18, 'boutique/shampoing chat.webp', 1),
(4,  'Shampooing Protecteur', 'Renforce la brillance et protège le poil.',        14.00, 12, 'boutique/shampoing protecteur.webp', 1),
(5,  'Gamelle pour chat',     'Céramique illustrée avec motifs félins.',          15.00, 10, 'boutique/gamel.webp', 1),
(6,  'Attrapes poil',         'Éponges spéciales pour vêtements et tissus.',       8.00, 25, 'boutique/attrape poil.webp', 1),
(7,  'Brosse en caoutchouc',  'Massage et retrait des poils morts.',               7.50, 30, 'boutique/brosse.webp', 1),
(8,  'Peigne anti-puces',     'Dents serrées en métal inoxydable.',                6.90, 22, 'boutique/peigne.webp', 1),
(9,  'Décapsuleur collier',   'Petit accessoire pratique en métal.',               5.00, 40, 'boutique/decapsuleur.webp', 1),
(10, 'Crochets à tique',      'Lot de 2 crochets pour retrait sécurisé.',          4.50, 35, 'boutique/tiques-2.webp', 1),
(11, 'Porte manteau',         'Décoration bois avec trois crochets.',             19.90,  8, 'boutique/porte mentaux.webp', 1),
(12, 'Balle pour chien',      'Balle résistante pour le jeu actif.',              11.00, 28, 'boutique/kong crunch.webp', 1);
