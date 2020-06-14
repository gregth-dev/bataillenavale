-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 07 mai 2020 à 07:53
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
-- Structure de la table `launchbattle`
--

DROP TABLE IF EXISTS `launchbattle`;
CREATE TABLE IF NOT EXISTS `launchbattle` (
  `idLaunchBattle` int(11) NOT NULL AUTO_INCREMENT,
  `idUser1` int(11) DEFAULT NULL,
  `idUser2` int(11) DEFAULT NULL,
  `statut` tinyint(1) DEFAULT NULL,
  `maxTime` datetime DEFAULT NULL,
  `readLaunch` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idLaunchBattle`)
) ENGINE=InnoDB AUTO_INCREMENT=420 DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
