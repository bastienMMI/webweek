-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 18 jan. 2026 à 17:04
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `spa43`
--

-- --------------------------------------------------------

--
-- Structure de la table `adoption`
--

CREATE TABLE `adoption` (
  `id_adoption` int(11) NOT NULL,
  `id_animal` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `date_adoption` date NOT NULL,
  `statut` enum('en_cours','validee','annulee') DEFAULT 'en_cours',
  `commentaire` text DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `animal`
--

CREATE TABLE `animal` (
  `id_animal` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `espece` enum('chat','chien','autre') NOT NULL,
  `age` int(11) DEFAULT NULL,
  `sexe` enum('masculin','feminin') NOT NULL,
  `description` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `statut` enum('disponible','adopte','reserve') DEFAULT 'disponible',
  `date_arrivee` date DEFAULT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `animal`
--

INSERT INTO `animal` (`id_animal`, `nom`, `espece`, `age`, `sexe`, `description`, `photo`, `statut`, `date_arrivee`, `date_ajout`, `date_modification`) VALUES
(1, 'Gire', 'chat', 2, 'masculin', 'Chat sociable et affectueux, idéal en appartement.', 'cookie.webp', 'disponible', '2025-12-15', '2026-01-18 13:05:16', '2026-01-18 13:05:16'),
(2, 'Backshoteur', 'chien', 4, 'masculin', 'Chien énergique qui adore les longues promenades.', 'dog1.webp', 'disponible', '2025-11-20', '2026-01-18 13:05:16', '2026-01-18 13:05:16'),
(3, 'Grimbert', 'chat', 1, 'masculin', 'Jeune chat joueur et très curieux.', 'roco.webp', 'disponible', '2026-01-05', '2026-01-18 13:05:16', '2026-01-18 13:05:16'),
(4, 'Belledent-Peyre', 'chat', 3, 'feminin', 'Chatte calme et indépendante.', 'felix.webp', 'disponible', '2025-10-10', '2026-01-18 13:05:16', '2026-01-18 13:05:16'),
(5, 'Mitraillette', 'chat', 5, 'feminin', 'Chatte douce qui aime les câlins.', 'garfield.webp', 'disponible', '2025-09-22', '2026-01-18 13:05:16', '2026-01-18 13:05:16'),
(6, 'Nicolas', 'chat', 2, 'masculin', 'Chat tigré très photogénique.', 'lynx.webp', 'reserve', '2025-12-01', '2026-01-18 13:05:16', '2026-01-18 13:05:16'),
(7, 'Anita', 'chien', 3, 'feminin', 'Chienne douce et obéissante.', 'minou.webp', 'disponible', '2025-11-15', '2026-01-18 13:05:16', '2026-01-18 13:05:16'),
(8, 'Beuf', 'chat', 1, 'masculin', 'Petit chaton plein de vie.', 'roco.webp', 'disponible', '2026-01-10', '2026-01-18 13:05:16', '2026-01-18 13:05:16'),
(9, 'Garfield', 'chat', 4, 'masculin', 'Grand chat roux qui adore manger.', 'lion.webp', 'disponible', '2025-08-30', '2026-01-18 13:05:16', '2026-01-18 13:05:16');

-- --------------------------------------------------------

--
-- Structure de la table `boutique`
--

CREATE TABLE `boutique` (
  `id_boutique` int(11) NOT NULL,
  `nom_boutique` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `boutique`
--

INSERT INTO `boutique` (`id_boutique`, `nom_boutique`, `description`, `adresse`, `date_creation`) VALUES
(1, 'Boutique SPA Haute-Loire', 'Boutique officielle de la SPA de Haute-Loire', '7 Impasse du Refuge ZA Plaine de Bleu, 43000 Polignac', '2026-01-18 13:05:16');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `date_commande` timestamp NOT NULL DEFAULT current_timestamp(),
  `montant_total` decimal(10,2) NOT NULL,
  `statut` enum('en_attente','validee','expediee','livree','annulee') DEFAULT 'en_attente',
  `adresse_livraison` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commande_produit`
--

CREATE TABLE `commande_produit` (
  `id_commande_produit` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `prix_unitaire` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `don`
--

CREATE TABLE `don` (
  `id_don` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_don` timestamp NOT NULL DEFAULT current_timestamp(),
  `statut` enum('en_attente','valide','refuse') DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(11) NOT NULL,
  `nom_produit` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `photo` varchar(255) DEFAULT NULL,
  `id_boutique` int(11) DEFAULT NULL,
  `actif` tinyint(1) DEFAULT 1,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `nom_produit`, `description`, `prix`, `stock`, `photo`, `id_boutique`, `actif`, `date_ajout`, `date_modification`) VALUES
(1, 'Shampoing solide', 'Soin naturel à l\'argile pour poils courts.', 12.50, 20, 'boutique/shampoing solide.webp', 1, 1, '2026-01-18 13:05:16', '2026-01-18 13:09:38'),
(2, 'Shampooing universel', 'Soin complet pour tous types de pelages.', 9.90, 15, 'boutique/shampoing universel.webp', 1, 1, '2026-01-18 13:05:16', '2026-01-18 13:09:44'),
(3, 'Shampooing Chat', 'Formule extra-douce respectant le PH.', 10.50, 18, 'boutique/shampoing chat.webp', 1, 1, '2026-01-18 13:05:16', '2026-01-18 13:09:51'),
(4, 'Shampooing Protecteur', 'Renforce la brillance et protège le poil.', 14.00, 12, 'boutique/shampoing protecteur.webp', 1, 1, '2026-01-18 13:05:16', '2026-01-18 13:10:05'),
(5, 'Gamelle pour chat', 'Céramique illustrée avec motifs félins.', 15.00, 10, 'boutique/gamel.webp', 1, 1, '2026-01-18 13:05:16', '2026-01-18 13:10:21'),
(6, 'Attrapes poil', 'Éponges spéciales pour vêtements et tissus.', 8.00, 25, 'boutique/attrape poil.webp', 1, 1, '2026-01-18 13:05:16', '2026-01-18 13:12:57'),
(7, 'Brosse en caoutchouc', 'Massage et retrait des poils morts.', 7.50, 30, 'boutique/brosse.webp', 1, 1, '2026-01-18 13:05:16', '2026-01-18 13:12:57'),
(8, 'Peigne anti-puces', 'Dents serrées en métal inoxydable.', 6.90, 22, 'boutique/peigne.webp', 1, 1, '2026-01-18 13:05:16', '2026-01-18 13:12:57'),
(9, 'Décapsuleur collier', 'Petit accessoire pratique en métal.', 5.00, 40, 'boutique/decapsuleur.webp', 1, 1, '2026-01-18 13:05:16', '2026-01-18 13:12:57'),
(10, 'Crochets à tique', 'Lot de 2 crochets pour retrait sécurisé.', 4.50, 35, 'boutique/tiques-2.webp', 1, 1, '2026-01-18 13:05:16', '2026-01-18 13:12:57'),
(11, 'Porte manteau', 'Décoration bois avec trois crochets.', 19.90, 8, 'boutique/porte mentaux.webp', 1, 1, '2026-01-18 13:05:16', '2026-01-18 13:12:57'),
(12, 'Balle pour chien', 'Balle résistante pour le jeu actif.', 11.00, 28, 'boutique/kong crunch.webp', 1, 1, '2026-01-18 13:05:16', '2026-01-18 13:12:57');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('client','admin') DEFAULT 'client',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `actif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `email`, `telephone`, `mot_de_passe`, `role`, `date_creation`, `actif`) VALUES
(1, 'Admin', 'Super', 'admin@spa43.fr', '0471026550', '$2y$10$YourHashedPasswordHere', 'admin', '2026-01-18 13:05:16', 1),
(2, 'Dupont', 'Jean', 'jean.dupont@email.fr', '0612345678', '$2y$10$YourHashedPasswordHere', 'client', '2026-01-18 13:05:16', 1),
(3, 'Martin', 'Sophie', 'sophie.martin@email.fr', '0623456789', '$2y$10$YourHashedPasswordHere', 'client', '2026-01-18 13:05:16', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adoption`
--
ALTER TABLE `adoption`
  ADD PRIMARY KEY (`id_adoption`),
  ADD KEY `id_animal` (`id_animal`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_statut` (`statut`);

--
-- Index pour la table `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`id_animal`),
  ADD KEY `idx_espece` (`espece`),
  ADD KEY `idx_statut` (`statut`),
  ADD KEY `idx_sexe` (`sexe`);

--
-- Index pour la table `boutique`
--
ALTER TABLE `boutique`
  ADD PRIMARY KEY (`id_boutique`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_statut` (`statut`),
  ADD KEY `idx_date` (`date_commande`);

--
-- Index pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD PRIMARY KEY (`id_commande_produit`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `don`
--
ALTER TABLE `don`
  ADD PRIMARY KEY (`id_don`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_date` (`date_don`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `id_boutique` (`id_boutique`),
  ADD KEY `idx_actif` (`actif`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adoption`
--
ALTER TABLE `adoption`
  MODIFY `id_adoption` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `animal`
--
ALTER TABLE `animal`
  MODIFY `id_animal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `boutique`
--
ALTER TABLE `boutique`
  MODIFY `id_boutique` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  MODIFY `id_commande_produit` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `don`
--
ALTER TABLE `don`
  MODIFY `id_don` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adoption`
--
ALTER TABLE `adoption`
  ADD CONSTRAINT `adoption_ibfk_1` FOREIGN KEY (`id_animal`) REFERENCES `animal` (`id_animal`) ON DELETE CASCADE,
  ADD CONSTRAINT `adoption_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD CONSTRAINT `commande_produit_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON DELETE CASCADE,
  ADD CONSTRAINT `commande_produit_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE CASCADE;

--
-- Contraintes pour la table `don`
--
ALTER TABLE `don`
  ADD CONSTRAINT `don_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`id_boutique`) REFERENCES `boutique` (`id_boutique`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
