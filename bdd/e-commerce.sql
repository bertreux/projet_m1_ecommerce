-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 21 juil. 2023 à 20:58
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `e-commerce`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

CREATE TABLE `adresse` (
  `id` int(11) NOT NULL,
  `intitule` varchar(250) NOT NULL,
  `ville` varchar(200) NOT NULL,
  `region` varchar(200) NOT NULL,
  `code_postal` varchar(5) NOT NULL,
  `pays` varchar(200) NOT NULL,
  `utilisateur_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ajouter`
--

CREATE TABLE `ajouter` (
  `id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `qte` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `nom`) VALUES
(1, 'Saison 1'),
(2, 'Saison 2'),
(3, 'Saison 3'),
(4, 'Saison 4'),
(5, 'Saison 5');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id` int(11) NOT NULL,
  `statut` varchar(100) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `adresse_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `compose`
--

CREATE TABLE `compose` (
  `id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `compose`
--

INSERT INTO `compose` (`id`, `produit_id`) VALUES
(1, 2, 1),
(2, 3, 1),
(3, 2, 2),
(4, 3, 2),
(5, 1, 3),
(6, 4, 3),
(7, 5, 4),
(8, 3, 4),
(9, 2, 5),
(10, 4, 5),
(11, 2, 6),
(12, 1, 7),
(13, 2, 7),
(14, 2, 8),
(15, 6, 8),
(16, 2, 9),
(17, 3, 10),
(18, 6, 10),
(19, 1, 11),
(20, 2, 11),
(21, 2, 12),
(22, 1, 13),
(23, 2, 13),
(24, 1, 14),
(25, 3, 14),
(26, 2, 15);

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `principal` tinyint(1) NOT NULL DEFAULT 0,
  `produit_id` int(11) DEFAULT NULL,
  `categorie_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `url`, `principal`, `produit_id`, `categorie_id`) VALUES
(28, 'images/Alter-Chronos.jpg', 1, 8, NULL),
(29, 'images/Diablos-Nemesis-X-D.jpg', 1, 7, NULL),
(30, 'images/Flash-Sagittario.jpg', 1, 5, NULL),
(31, 'images/Galaxy-Pegasus.jpg', 1, 1, NULL),
(32, 'images/Glide-Ragnaruk-Wheel-Revolve-1S.jpg', 1, 10, NULL),
(33, 'images/Gravity-Destroyer.jpg', 1, 13, NULL),
(34, 'images/Killer-Deathscyther.jpg', 0, 9, NULL),
(35, 'images/Kreis-Cygnus.jpg', 0, 14, NULL),
(36, 'images/Lucifer-the-End-Kou-Drift.jpg', 1, 4, NULL),
(37, 'images/Meteo-L-Drago.jpg', 0, 2, NULL),
(38, 'images/Phantom-Orion-BD.jpg', 0, 15, NULL),
(39, 'images/Poison-Serpent.jpg', 0, 6, NULL),
(40, 'images/Thermal-Lacerta.jpg', 1, 12, NULL),
(41, 'images/Variant-Lucifer-Mobius-2D.jpg', 0, 11, NULL),
(42, 'images/Venom-Diabolos-Vanguard-Bullet.jpg', 1, 3, NULL),
(43, 'images/Beyblade-Metal-Fusion-S1.jpg', 0, NULL, 1),
(44, 'images/Beyblades-saison-2.jpg', 0, NULL, 2),
(45, 'images/Beyblades-saison-3.jpg', 0, NULL, 3),
(46, 'images/Beyblades-saison-4.jpg', 0, NULL, 4),
(47, 'images/Beyblades-S5.jpg', 0, NULL, 5);

-- --------------------------------------------------------

--
-- Structure de la table `mail`
--

CREATE TABLE `mail` (
  `objet` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `prix` float NOT NULL,
  `stock` int(10) NOT NULL,
  `description` text NOT NULL,
  `carousel` tinyint(1) NOT NULL DEFAULT 0,
  `highlander` tinyint(1) NOT NULL DEFAULT 0,
  `arriver` date NOT NULL DEFAULT current_timestamp(),
  `prioriter` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `nom`, `prix`, `stock`, `description`, `carousel`, `highlander`, `arriver`, `prioriter`, `categorie_id`) VALUES
(1, 'Galaxy Pegasus', 40, 1000, 'Face avant Pegasus', 'Anneau énergétique Pegasus', 'Roue de Fusion Galaxy', 'Spin track W105', 'Pointe de performance Right Rubber Flat', 1, 0, '2023-07-21', 1, 2);
(2, 'Meteo L Drago', 30, 1000,  'Face avant L-Drago', 'Anneau énergétique Striker', 'Roue de Fusion Meteo', 'Spin track LW105', 'Pointe de performance Left Flat', 0, 0, '2023-07-21', 3, 2),
(3, 'Venom Diabolos Vanguard Bullet', 70, 700, 'Puce Gatinko Diabolos', 'Couche de base à rotation droite Venom', 'Couche de base à rotation gauche Erase', 'Disque forgé Vanguard', 'Pointe de performance Bullet', 0, 1, '2023-07-21', 2, 4),
(4, 'Lucifer the End Kou Drift', 50, 1000,'Puce Superking Lucifer', 'Anneau End', 'Disque forgé Kou', 'Pointe de performance Drift', 1, 1, '2023-07-21', 1, 5),
(5, 'Flash Sagittario', 39, 1000, 'Face avant Sagittario II', 'Anneau énergétique Sagittario II', 'Roue de Fusion Flash', 'Axe de rotation 230', 'Pointe de performance WD', 0, 1, '2023-07-21', 1, 3),
(6, 'Poison Serpent', 20, 1000, 'Face avant Serpent', 'Roue de Fusion Poison', 'Spin track Switch 145', 'Pointe de performance Semi-Defense', 0, 0, '2023-07-21', 2, 1),
(7, 'Diablo Nemesis XD', 50, 1000, 'Face avant Nemesis A', 'Anneau énergétique Nemesis A', 'Roue de Fusion Diablo', 'Pointe de performance X Drive', 1, 0, '2023-07-21', 1, 3),
(8, 'Alter Chronos 6Meteor Trans', 80, 1000, 'Anneau énergétique Alter Chronos', 'Disque forgé 6', 'Cadre Meteor', 'Pointe de performance Trans', 0, 1, '2023-07-21', 3, 2),
(9, 'Killer Deathscyther 2Vortex Hunter', 90, 1000, 'Anneau énergétique Killer Deathscyther', 'Disque forgé 2', 'Cadre Vortex', 'Pointe de performance Hunter', 0, 1, '2023-07-21', 3, 2),
(10, 'Glide Ragnaruk Wheel Revolve 1S', 40, 1000, 'Puce Superking Ragnaruk ', 'Anneau Glide', 'Châssis 1S', 'Disque forgé Wheel ', 'Pointe de performance Revolve', 0, 0, '2023-07-21', 3, 5),
(11, 'Variant Lucifer Mobius 2D', 70, 1000, 'Puce Superking Lucifer ', 'Anneau Variant', 'Châssis 2D', 'Pointe de performance Mobius', 0, 1, '2023-07-21', 2, 5),
(12, 'Thermal Lacerta', 35, 1000, 'Face avant Lacerta', 'Anneau énergétique Lacerta', 'Roue de Fusion Thermal', 'Spin track WA130', 'Pointe de performance Hole Flat', 0, 0, '2023-07-21', 2, 2),
(13, 'Gravity Destroyer', 50, 1000,'Face avant Destroyer / Perseus', 'Anneau énergétique Destroyer / Perseus', 'Roue de Fusion Gravity', 'Spin track AD145', 'Pointe de performance Wide Defense', 1, 1, '2023-07-21', 1, 2),
(14, 'Kreis Cygnus', 40, 1000,'Face avant Cygnus', 'Anneau énergétique Cygnus', 'Roue de Fusion Kreis', 'Spin track 145', 'Pointe de performance Wide Defense', 0, 1, '2023-07-21', 3, 3),
(15, 'Phantom Orion BD', 30, 1000,'Face avant Orion', 'Anneau énergétique Orion', 'Roue de Fusion Phantom', 'Pointe de performance Bearing Drive' , 0, 1, '2023-07-21', 2, 4);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `prenom` varchar(150) NOT NULL,
  `tel` varchar(10) NOT NULL,
  `email` varchar(250) NOT NULL,
  `mdp` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE` (`utilisateur_id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`) USING BTREE;

--
-- Index pour la table `ajouter`
--
ALTER TABLE `ajouter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_produit` (`produit_id`) USING BTREE,
  ADD KEY `id_commande` (`commande_id`) USING BTREE;

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`utilisateur_id`) USING BTREE,
  ADD KEY `commande_adresse_id_fk` (`adresse_id`);

--
-- Index pour la table `compose`
--
ALTER TABLE `compose`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_produit` (`produit_id`) USING BTREE;

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mail`
--
ALTER TABLE `mail`
  ADD PRIMARY KEY (`id`);


--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categorie` (`categorie_id`) USING BTREE;

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adresse`
--
ALTER TABLE `adresse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `ajouter`
--
ALTER TABLE `ajouter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `compose`
--
ALTER TABLE `compose`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT pour la table `mail`
--
ALTER TABLE `mail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;


--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD CONSTRAINT `cle_utili` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `ajouter`
--
ALTER TABLE `ajouter`
  ADD CONSTRAINT `cle_commande` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cle_produit` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `cle_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commande_adresse_id_fk` FOREIGN KEY (`adresse_id`) REFERENCES `adresse` (`id`);

--
-- Contraintes pour la table `compose`
--
ALTER TABLE `compose`
  ADD CONSTRAINT `cle_produi` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `cle_categorie` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
