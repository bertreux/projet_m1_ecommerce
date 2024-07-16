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

SET FOREIGN_KEY_CHECKS = 0;
truncate table produit ;
truncate table image;
truncate table categorie;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `categorie` (`id`, `nom`) VALUES
    (1, 'Saison 1'),
    (2, 'Saison 2'),
    (3, 'Saison 3'),
    (4, 'Saison 4'),
    (5, 'Saison 5');

-- --------------------------------------------------------

INSERT INTO `produit` (`id`, `nom`, `prix`, `stock`, `description`, `carousel`, `highlander`, `arriver`, `prioriter`, `categorie_id`) VALUES
    (1, 'Galaxy Pegasus', 40, 1000, 'Face avant Pegasus, Anneau énergétique Pegasus, Roue de Fusion Galaxy, Spin track W105, Pointe de performance Right Rubber Flat', 1, 0, '2023-07-21', 1, 2),
    (2, 'Meteo L Drago', 30, 1000,  'Face avant L-Drago, Anneau énergétique Striker, Roue de Fusion Meteo, Spin track LW105, Pointe de performance Left Flat', 0, 0, '2023-07-21', 3, 2),
    (3, 'Venom Diabolos Vanguard Bullet', 70, 700, 'Puce Gatinko Diabolos, Couche de base à rotation droite Venom, Couche de base à rotation gauche Erase, Disque forgé Vanguard, Pointe de performance Bullet', 0, 1, '2023-07-21', 2, 4),
    (4, 'Lucifer the End Kou Drift', 50, 1000,'Puce Superking Lucifer, Anneau End, Disque forgé Kou, Pointe de performance Drift', 1, 1, '2023-07-21', 1, 5),
    (5, 'Flash Sagittario', 39, 1000, 'Face avant Sagittario II, Anneau énergétique Sagittario II, Roue de Fusion Flash, Axe de rotation 230, Pointe de performance WD', 0, 1, '2023-07-21', 1, 3),
    (6, 'Poison Serpent', 20, 1000, 'Face avant Serpent, Roue de Fusion Poison, Spin track Switch 145, Pointe de performance Semi-Defense', 0, 0, '2023-07-21', 2, 1),
    (7, 'Diablo Nemesis XD', 50, 1000, 'Face avant Nemesis A, Anneau énergétique Nemesis A, Roue de Fusion Diablo, Pointe de performance X Drive', 1, 0, '2023-07-21', 1, 3),
    (8, 'Alter Chronos 6Meteor Trans', 80, 1000, 'Anneau énergétique Alter Chronos, Disque forgé 6, Cadre Meteor, Pointe de performance Trans', 0, 1, '2023-07-21', 3, 2),
    (9, 'Killer Deathscyther 2Vortex Hunter', 90, 1000, 'Anneau énergétique Killer Deathscyther, Disque forgé 2, Cadre Vortex, Pointe de performance Hunter', 0, 1, '2023-07-21', 3, 2),
    (10, 'Glide Ragnaruk Wheel Revolve 1S', 40, 1000, 'Puce Superking Ragnaruk , Anneau Glide, Châssis 1S, Disque forgé Wheel , Pointe de performance Revolve', 0, 0, '2023-07-21', 3, 5),
    (11, 'Variant Lucifer Mobius 2D', 70, 1000, 'Puce Superking Lucifer , Anneau Variant, Châssis 2D, Pointe de performance Mobius', 0, 1, '2023-07-21', 2, 5),
    (12, 'Thermal Lacerta', 35, 1000, 'Face avant Lacerta, Anneau énergétique Lacerta, Roue de Fusion Thermal, Spin track WA130, Pointe de performance Hole Flat', 0, 0, '2023-07-21', 2, 2),
    (13, 'Gravity Destroyer', 50, 1000,'Face avant Destroyer / Perseus, Anneau énergétique Destroyer / Perseus, Roue de Fusion Gravity, Spin track AD145, Pointe de performance Wide Defense', 1, 1, '2023-07-21', 1, 2),
    (14, 'Kreis Cygnus', 40, 1000,'Face avant Cygnus, Anneau énergétique Cygnus, Roue de Fusion Kreis, Spin track 145, Pointe de performance Wide Defense', 0, 1, '2023-07-21', 3, 3),
    (15, 'Phantom Orion BD', 30, 1000,'Face avant Orion, Anneau énergétique Orion, Roue de Fusion Phantom, Pointe de performance Bearing Drive' , 0, 1, '2023-07-21', 2, 4);

-- --------------------------------------------------------

INSERT INTO `image` (`id`, `url`, `principal`, `produit_id`, `categorie_id`) VALUES
    (1, 'images/Alter-Chronos.jpg', 1, 8, NULL),
    (2, 'images/Diablo-Nemesis-X-D.jpg', 1, 7, NULL),
    (3, 'images/Flash-Sagittario.jpg', 1, 5, NULL),
    (4, 'images/Galaxy-Pegasus.jpg', 1, 1, NULL),
    (5, 'images/Glide-Ragnaruk-Wheel-Revolve-1S.jpg', 1, 10, NULL),
    (6, 'images/Gravity-Destroyer.jpg', 1, 13, NULL),
    (7, 'images/Killer-Deathscyther.jpg', 0, 9, NULL),
    (8, 'images/Kreis-Cygnus.jpg', 0, 14, NULL),
    (9, 'images/Lucifer-the-End-Kou-Drift.jpg', 1, 4, NULL),
    (10, 'images/Meteo-L-Drago.jpg', 0, 2, NULL),
    (11, 'images/Phantom-Orion-BD.jpg', 0, 15, NULL),
    (12, 'images/Poison-Serpent.jpg', 0, 6, NULL),
    (13, 'images/Thermal-Lacerta.jpg', 1, 12, NULL),
    (14, 'images/Variant-Lucifer-Mobius-2D.jpg', 0, 11, NULL),
    (15, 'images/Venom-Diabolos-Vanguard-Bullet.jpg', 1, 3, NULL),
    (16, 'images/Beyblade-Metal-Fusion-S1.jpg', 0, NULL, 1),
    (17, 'images/Beyblades-saison-2.jpg', 0, NULL, 2),
    (18, 'images/Beyblades-saison-3.jpg', 0, NULL, 3),
    (19, 'images/Beyblades-saison-4.jpg', 0, NULL, 4),
    (20, 'images/Beyblades-S5.jpg', 0, NULL, 5);

-- --------------------------------------------------------

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
