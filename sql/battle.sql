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
-- Structure de la table `battle`
--

DROP TABLE IF EXISTS `battle`;
CREATE TABLE IF NOT EXISTS `battle` (
  `idBattle` int(11) NOT NULL AUTO_INCREMENT,
  `idLaunchBattle` int(11) DEFAULT NULL,
  `idUser1` int(11) DEFAULT NULL,
  `idUser2` int(11) DEFAULT NULL,
  `ready` tinyint(1) DEFAULT '0',
  `miss` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `touch` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bombing` varchar(15) DEFAULT NULL,
  `megaBomb` varchar(45) DEFAULT NULL,
  `repair` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`idBattle`)
) ENGINE=InnoDB AUTO_INCREMENT=604 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `battle`
--

INSERT INTO `battle` (`idBattle`, `idLaunchBattle`, `idUser1`, `idUser2`, `ready`, `miss`, `touch`, `bombing`, `megaBomb`, `repair`) VALUES
(602, 419, 22, 8, 0, NULL, NULL, NULL, NULL, NULL),
(603, 419, 8, 22, 0, NULL, NULL, NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
