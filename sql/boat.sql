-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 08 mai 2020 à 07:38
-- Version du serveur :  8.0.18
-- Version de PHP :  7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bataillenavale`
--

-- --------------------------------------------------------

--
-- Structure de la table `boat`
--

DROP TABLE IF EXISTS `boat`;
CREATE TABLE IF NOT EXISTS `boat` (
  `idBoat` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `positions` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direction` tinyint(1) DEFAULT NULL,
  `count` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idBoat`)
) ENGINE=InnoDB AUTO_INCREMENT=786 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `boat`
--

INSERT INTO `boat` (`idBoat`, `idUser`, `name`, `positions`, `direction`, `count`) VALUES
(776, 29, 'porteAvions', 'F6,G6,H6,I6,J6', 2, 5),
(777, 29, 'croiseur', 'G2,G3,G4,G5', 1, 4),
(778, 29, 'sousMarin', 'G8,G9,G10', 1, 3),
(779, 29, 'contreTorpilleur', 'C6,C7,C8', 1, 3),
(780, 29, 'torpilleur', 'C3,D3', 2, 2),
(781, 22, 'porteAvions', 'J5,J6,J7,J8,J9', 1, 5),
(782, 22, 'croiseur', 'B7,C7,D7,E7', 2, 4),
(783, 22, 'contreTorpilleur', 'H5,H6,H7', 1, 3),
(784, 22, 'sousMarin', 'C4,D4,E4', 2, 3),
(785, 22, 'torpilleur', 'A1,A2', 1, 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
