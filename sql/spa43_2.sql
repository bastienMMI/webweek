-- ============================================================
--  SPA Haute-Loire — Schéma cible de la base de données
--  SAE301 (rattrapage) — refonte technique
--  Objectifs :
--    1. Supprimer l'e-commerce (interdit par le sujet)
--    2. Enrichir les informations sur les animaux
--    3. Ajouter la pré-réservation gratuite + les messages de contact
-- ============================================================

-- ------------------------------------------------------------
-- 1. Suppression des tables e-commerce
--    (vente en ligne interdite : on remplace par la réservation)
-- ------------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS commande_produit;
DROP TABLE IF EXISTS commande;
DROP TABLE IF EXISTS produit;
DROP TABLE IF EXISTS boutique;
SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------
-- 2. Enrichissement de la table `animal`
--    - infos demandées par le sujet : vacciné / stérilisé / identifié
--    - date_naissance à la place de l'âge (âge calculé -> plus juste)
--    - correction des ENUM (alignés avec les formulaires)
-- ------------------------------------------------------------
ALTER TABLE `animal`
  MODIFY `espece` ENUM('chien','chat','nac','autre') NOT NULL,
  MODIFY `statut` ENUM('disponible','reserve','adopte') NOT NULL DEFAULT 'disponible',
  ADD COLUMN `date_naissance` DATE       DEFAULT NULL      AFTER `sexe`,
  ADD COLUMN `vaccine`        TINYINT(1) NOT NULL DEFAULT 0 AFTER `description`,
  ADD COLUMN `sterilise`      TINYINT(1) NOT NULL DEFAULT 0 AFTER `vaccine`,
  ADD COLUMN `identifie`      TINYINT(1) NOT NULL DEFAULT 0 AFTER `sterilise`;

-- Une fois les `date_naissance` renseignées, on pourra retirer l'ancienne colonne :
-- ALTER TABLE `animal` DROP COLUMN `age`;

-- ------------------------------------------------------------
-- 3. Nouvelle table `reservation`
--    Pré-réservation GRATUITE d'un animal par un utilisateur.
--    Remplace le couple commande / commande_produit.
-- ------------------------------------------------------------
CREATE TABLE `reservation` (
  `id_reservation`   INT(11)   NOT NULL AUTO_INCREMENT,
  `id_utilisateur`   INT(11)   NOT NULL,
  `id_animal`        INT(11)   NOT NULL,
  `date_reservation` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `statut`           ENUM('en_attente','confirmee','annulee','concretisee')
                     NOT NULL DEFAULT 'en_attente',
  `message`          TEXT      DEFAULT NULL,
  PRIMARY KEY (`id_reservation`),
  CONSTRAINT `fk_resa_user`
    FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  CONSTRAINT `fk_resa_animal`
    FOREIGN KEY (`id_animal`)      REFERENCES `animal` (`id_animal`)           ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 4. Nouvelle table `message_contact`
--    Rend le formulaire de contact réellement fonctionnel
--    (aujourd'hui purement décoratif côté front).
-- ------------------------------------------------------------
CREATE TABLE `message_contact` (
  `id_message` INT(11)      NOT NULL AUTO_INCREMENT,
  `nom`        VARCHAR(100) NOT NULL,
  `email`      VARCHAR(150) NOT NULL,
  `telephone`  VARCHAR(20)  DEFAULT NULL,
  `objet`      VARCHAR(150) DEFAULT NULL,
  `message`    TEXT         NOT NULL,
  `date_envoi` TIMESTAMP    NOT NULL DEFAULT current_timestamp(),
  `traite`     TINYINT(1)   NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_message`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Tables conservées telles quelles :
--   `utilisateur` (id, nom, prenom, email, telephone,
--                  mot_de_passe [haché], role, date_creation, actif)
--   `don`         (id, id_utilisateur*, nom, prenom, email,
--                  telephone, montant, date_don, statut)
-- ------------------------------------------------------------
