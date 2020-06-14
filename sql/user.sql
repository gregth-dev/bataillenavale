-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 26 avr. 2020 à 22:02
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
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `role` tinyint(4) DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `avatar` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restoreCode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `timeRestore` datetime DEFAULT NULL,
  PRIMARY KEY (`idUser`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`idUser`, `email`, `password`, `role`, `name`, `score`, `avatar`, `restoreCode`, `timeRestore`) VALUES
(8, 'gregory.thorel@live.fr', '$2y$10$fQ3srxRpXgJ6a7no996UNu9hQ2a/ET55CEzZ3TWY6OpS5x3M0XYnS', 1, 'Greg', 2270, '3', '5ea5aa68734ac', '2020-04-27 15:36:00'),
(21, 'test@test.fr', '$2y$10$RdF93KpDK8Ycddum4gafz..HvAP/xnbefV.DQO6z.9JKMbhlzinP2', 0, 'SuperGamer', 2095, '10', NULL, NULL),
(22, 'boap@boap.com', '$2y$10$3/v1YVDb68J92br2B9PRj.2FKU9OtFpLJWfup9NCoK6n9jeLIhRru', 0, 'Boap', 2580, '2', '5ea5d23fb5dec', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
