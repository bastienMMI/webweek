-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 20 juil. 2026 à 23:24
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
-- Structure de la table `animal`
--

CREATE TABLE `animal` (
  `id_animal` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `espece` enum('chien','chat','nac','autre') NOT NULL,
  `age` int(11) DEFAULT NULL,
  `sexe` enum('masculin','feminin') NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `vaccine` tinyint(1) NOT NULL DEFAULT 0,
  `sterilise` tinyint(1) NOT NULL DEFAULT 0,
  `identifie` tinyint(1) NOT NULL DEFAULT 0,
  `photo` varchar(255) DEFAULT NULL,
  `statut` enum('disponible','reserve','adopte') NOT NULL DEFAULT 'disponible',
  `date_arrivee` date DEFAULT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `animal`
--

INSERT INTO `animal` (`id_animal`, `nom`, `espece`, `age`, `sexe`, `date_naissance`, `description`, `vaccine`, `sterilise`, `identifie`, `photo`, `statut`, `date_arrivee`, `date_ajout`, `date_modification`) VALUES
(1, 'jakson', 'chat', 2, 'masculin', '2025-12-25', 'Chat sociable et affectueux, idéal en appartement.', 1, 0, 1, 'jak.jpg', 'disponible', '2025-12-15', '2026-01-18 13:05:16', '2026-07-20 18:35:18'),
(3, 'Grimbert', 'chien', 1, 'masculin', '2020-11-30', 'Chien joueur et affectueux, aime les enfants et ses congénères.', 1, 1, 1, 'gr.webp', 'disponible', '2026-01-05', '2026-01-18 13:05:16', '2026-07-20 18:33:48'),
(4, 'mimi', 'chat', 3, 'feminin', '2024-09-17', 'Chatte calme et indépendante.', 1, 1, 1, 'mimi.jpg', 'disponible', '2025-10-10', '2026-01-18 13:05:16', '2026-07-20 18:31:33'),
(5, 'vernom', 'chien', 5, 'masculin', NULL, 'Chien abandonné après décès, très affectueux', 1, 0, 1, 'vernom.jpg', 'disponible', '2025-09-22', '2026-01-18 13:05:16', '2026-07-20 18:36:48'),
(6, 'reglisse', 'chat', 2, 'feminin', '2021-07-09', 'Chat noir très photogénique.', 1, 1, 1, 'reg.jpg', 'reserve', '2025-12-01', '2026-01-18 13:05:16', '2026-07-20 18:36:42'),
(7, 'Anita', 'chien', 3, 'feminin', NULL, 'Chienne douce et obéissante. Age approximatif : 8 ans', 1, 1, 1, 'anita.jpg', 'disponible', '2025-11-15', '2026-01-18 13:05:16', '2026-07-20 18:36:39'),
(8, 'Aiko', 'chat', 1, 'masculin', '2026-04-14', 'Petit chaton plein de vie.', 1, 0, 1, 'aiko.jpg', 'disponible', '2026-01-10', '2026-01-18 13:05:16', '2026-07-20 18:24:14'),
(9, 'Garfield', 'chat', 4, 'masculin', '2024-08-01', 'Grand chat roux qui adore manger.', 0, 1, 1, 'garfield.jpg', 'disponible', '2025-08-30', '2026-01-18 13:05:16', '2026-07-20 18:36:34');

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

--
-- Déchargement des données de la table `don`
--

INSERT INTO `don` (`id_don`, `id_utilisateur`, `nom`, `prenom`, `email`, `telephone`, `montant`, `date_don`, `statut`) VALUES
(1, 4, 'Lavest', 'Bastien', 'bastien.lavest@gmail.com', '0781405816', 20.00, '2026-07-20 17:41:27', 'valide'),
(2, 4, 'Lavest', 'Bastien', 'bastien.lavest@gmail.com', '0781405816', 50.00, '2026-07-20 18:07:53', 'valide');

-- --------------------------------------------------------

--
-- Structure de la table `message_contact`
--

CREATE TABLE `message_contact` (
  `id_message` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `objet` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `date_envoi` timestamp NOT NULL DEFAULT current_timestamp(),
  `traite` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `message_contact`
--

INSERT INTO `message_contact` (`id_message`, `nom`, `email`, `telephone`, `objet`, `message`, `date_envoi`, `traite`) VALUES
(1, 'Bastien Lavest', 'bastien.lavest@gmail.com', '0781405816', 'demande', 'demande de reserver beuf', '2026-07-20 18:05:09', 0);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(11) NOT NULL,
  `nom_produit` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `photo` varchar(255) DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `nom_produit`, `description`, `prix`, `stock`, `photo`, `actif`, `date_ajout`, `date_modification`) VALUES
(1, 'Shampoing solide', 'Soin naturel à l\'argile pour poils courts.', 12.50, 20, 'boutique/shampoing solide.webp', 1, '2026-07-20 17:37:52', '2026-07-20 17:37:52'),
(2, 'Shampooing universel', 'Soin complet pour tous types de pelages.', 9.90, 15, 'boutique/shampoing universel.webp', 1, '2026-07-20 17:37:52', '2026-07-20 17:37:52'),
(3, 'Shampooing Chat', 'Formule extra-douce respectant le PH.', 10.50, 18, 'boutique/shampoing chat.webp', 1, '2026-07-20 17:37:52', '2026-07-20 17:37:52'),
(4, 'Shampooing Protecteur', 'Renforce la brillance et protège le poil.', 14.00, 12, 'boutique/shampoing protecteur.webp', 1, '2026-07-20 17:37:52', '2026-07-20 17:37:52'),
(5, 'Gamelle pour chat', 'Céramique illustrée avec motifs félins.', 15.00, 10, 'boutique/gamel.webp', 1, '2026-07-20 17:37:52', '2026-07-20 17:37:52'),
(6, 'Attrapes poil', 'Éponges spéciales pour vêtements et tissus.', 8.00, 25, 'boutique/attrape poil.webp', 1, '2026-07-20 17:37:52', '2026-07-20 17:37:52'),
(7, 'Brosse en caoutchouc', 'Massage et retrait des poils morts.', 7.50, 30, 'boutique/brosse.webp', 1, '2026-07-20 17:37:52', '2026-07-20 17:37:52'),
(8, 'Peigne anti-puces', 'Dents serrées en métal inoxydable.', 6.90, 22, 'boutique/peigne.webp', 1, '2026-07-20 17:37:52', '2026-07-20 17:37:52'),
(9, 'Décapsuleur collier', 'Petit accessoire pratique en métal.', 5.00, 40, 'boutique/decapsuleur.webp', 1, '2026-07-20 17:37:52', '2026-07-20 17:37:52'),
(10, 'Crochets à tique', 'Lot de 2 crochets pour retrait sécurisé.', 4.50, 35, 'boutique/tiques-2.webp', 1, '2026-07-20 17:37:52', '2026-07-20 17:37:52'),
(11, 'Porte manteau', 'Décoration bois avec trois crochets.', 19.90, 8, 'boutique/porte mentaux.webp', 1, '2026-07-20 17:37:52', '2026-07-20 17:37:52'),
(12, 'Balle pour chien', 'Balle résistante pour le jeu actif.', 11.00, 28, 'boutique/kong crunch.webp', 1, '2026-07-20 17:37:52', '2026-07-20 17:37:52');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id_reservation` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_animal` int(11) NOT NULL,
  `date_reservation` timestamp NOT NULL DEFAULT current_timestamp(),
  `statut` enum('en_attente','confirmee','annulee','concretisee') NOT NULL DEFAULT 'en_attente',
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`id_reservation`, `id_utilisateur`, `id_animal`, `date_reservation`, `statut`, `message`) VALUES
(1, 4, 9, '2026-07-20 18:06:15', 'annulee', 'erttez\'ttez\'t');

-- --------------------------------------------------------

--
-- Structure de la table `reservation_produit`
--

CREATE TABLE `reservation_produit` (
  `id_reservation_produit` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `date_reservation` timestamp NOT NULL DEFAULT current_timestamp(),
  `statut` enum('en_attente','prete','retiree','annulee') NOT NULL DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(3, 'Martin', 'Sophie', 'sophie.martin@email.fr', '0623456789', '$2y$10$YourHashedPasswordHere', 'client', '2026-01-18 13:05:16', 1),
(4, 'Lavest', 'Bastien', 'bastien.lavest@gmail.com', '0781405816', '$2y$10$A658oJVxJEfVI5HbLkl97.tM/vVQPVOvUZPAjU4dm.YXo2f3kHu12', 'admin', '2026-07-20 17:39:59', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`id_animal`),
  ADD KEY `idx_espece` (`espece`),
  ADD KEY `idx_statut` (`statut`),
  ADD KEY `idx_sexe` (`sexe`);

--
-- Index pour la table `don`
--
ALTER TABLE `don`
  ADD PRIMARY KEY (`id_don`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_date` (`date_don`);

--
-- Index pour la table `message_contact`
--
ALTER TABLE `message_contact`
  ADD PRIMARY KEY (`id_message`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `fk_resa_user` (`id_utilisateur`),
  ADD KEY `fk_resa_animal` (`id_animal`);

--
-- Index pour la table `reservation_produit`
--
ALTER TABLE `reservation_produit`
  ADD PRIMARY KEY (`id_reservation_produit`),
  ADD KEY `fk_resaprod_user` (`id_utilisateur`),
  ADD KEY `fk_resaprod_produit` (`id_produit`);

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
-- AUTO_INCREMENT pour la table `animal`
--
ALTER TABLE `animal`
  MODIFY `id_animal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `don`
--
ALTER TABLE `don`
  MODIFY `id_don` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `message_contact`
--
ALTER TABLE `message_contact`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `reservation_produit`
--
ALTER TABLE `reservation_produit`
  MODIFY `id_reservation_produit` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `don`
--
ALTER TABLE `don`
  ADD CONSTRAINT `don_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL;

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_resa_animal` FOREIGN KEY (`id_animal`) REFERENCES `animal` (`id_animal`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_resa_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reservation_produit`
--
ALTER TABLE `reservation_produit`
  ADD CONSTRAINT `fk_resaprod_produit` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_resaprod_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
