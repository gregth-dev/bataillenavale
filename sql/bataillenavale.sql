-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: mysql-gregorythorel.alwaysdata.net
-- Generation Time: May 22, 2020 at 09:45 PM
-- Server version: 10.4.12-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gregorythorel_bn`
--

-- --------------------------------------------------------

--
-- Table structure for table `battle`
--

CREATE TABLE `battle` (
  `idBattle` int(11) NOT NULL,
  `idLaunchBattle` int(11) DEFAULT NULL,
  `idUser1` int(11) DEFAULT NULL,
  `idUser2` int(11) DEFAULT NULL,
  `ready` tinyint(1) DEFAULT 0,
  `miss` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `touch` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bombing` varchar(15) DEFAULT NULL,
  `megaBomb` varchar(45) DEFAULT NULL,
  `repair` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `boat`
--

CREATE TABLE `boat` (
  `idBoat` int(11) NOT NULL,
  `idUser` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `positions` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direction` tinyint(1) DEFAULT NULL,
  `count` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `boat`
--

INSERT INTO `boat` (`idBoat`, `idUser`, `name`, `positions`, `direction`, `count`) VALUES
(831, 29, 'porteAvions', 'F2,G2,H2,I2,J2', 2, 5),
(832, 29, 'contreTorpilleur', 'F10,G10,H10', 2, 3),
(833, 29, 'sousMarin', 'G5,G6,G7', 1, 3),
(834, 29, 'torpilleur', 'B6,B7', 1, 2),
(835, 29, 'croiseur', 'A1,B1,C1,D1', 2, 4),
(876, 30, 'porteAvions', 'C7,D7,E7,F7,G7', 2, 5),
(877, 30, 'croiseur', 'H3,H4,H5,H6', 1, 4),
(878, 30, 'sousMarin', 'E8,F8,G8', 2, 3),
(879, 30, 'torpilleur', 'A1,B1', 2, 2),
(880, 30, 'contreTorpilleur', 'J2,J3,J4', 1, 3),
(881, 31, 'porteAvions', 'B6,B7,B8,B9,B10', 1, 5),
(882, 31, 'sousMarin', 'G7,H7,I7', 2, 3),
(883, 31, 'torpilleur', 'E10,F10', 2, 2),
(884, 31, 'croiseur', 'F2,G2,H2,I2', 2, 4),
(885, 31, 'contreTorpilleur', 'H1,I1,J1', 2, 3),
(1826, 32, 'torpilleur', 'G8,G9', 1, 2),
(1827, 32, 'porteAvions', 'A5,B5,C5,D5,E5', 2, 5),
(1828, 32, 'contreTorpilleur', 'I5,I6,I7', 1, 3),
(1829, 32, 'sousMarin', 'D8,E8,F8', 2, 3),
(1830, 32, 'croiseur', 'E6,F6,G6,H6', 2, 4),
(1856, 22, 'porteAvions', 'C5,C6,C7,C8,C9', 1, 5),
(1857, 22, 'croiseur', 'I4,I5,I6,I7', 1, 4),
(1858, 22, 'sousMarin', 'H1,H2,H3', 1, 3),
(1859, 22, 'torpilleur', 'F3,F4', 1, 2),
(1860, 22, 'contreTorpilleur', 'J8,J9,J10', 1, 3),
(1861, 35, 'porteAvions', 'C1,C2,C3,C4,C5', 1, 5),
(1862, 35, 'contreTorpilleur', 'E8,E9,E10', 1, 3),
(1863, 35, 'croiseur', 'A6,B6,C6,D6', 2, 4),
(1864, 35, 'sousMarin', 'D8,D9,D10', 1, 3),
(1865, 35, 'torpilleur', 'E1,F1', 2, 2),
(1866, 8, 'porteAvions', 'D2,D3,D4,D5,D6', 1, 5),
(1867, 8, 'croiseur', 'F6,G6,H6,I6', 2, 4),
(1868, 8, 'sousMarin', 'H3,H4,H5', 1, 3),
(1869, 8, 'torpilleur', 'B2,B3', 1, 2),
(1870, 8, 'contreTorpilleur', 'E8,E9,E10', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `launchBattle`
--

CREATE TABLE `launchBattle` (
  `idLaunchBattle` int(11) NOT NULL,
  `idUser1` int(11) DEFAULT NULL,
  `idUser2` int(11) DEFAULT NULL,
  `statut` tinyint(1) DEFAULT NULL,
  `maxTime` datetime DEFAULT NULL,
  `readLaunch` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `idMessage` int(11) NOT NULL,
  `idUser` int(11) DEFAULT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`idMessage`, `idUser`, `content`, `date`, `private`) VALUES
(202, 33, 'yop', '2020-05-14 18:23:39', 0),
(203, 33, 'Bravo', '2020-05-14 18:24:10', 0),
(204, 22, 'comment ca va bien?????', '2020-05-14 18:24:26', 0),
(205, 22, 'youhouuuu', '2020-05-14 18:24:42', 0),
(206, 33, 'salut', '2020-05-14 18:30:17', 0),
(211, 22, 'test', '2020-05-14 18:55:26', 0),
(212, 8, 'Test', '2020-05-15 22:11:24', 0),
(213, 34, 'salut', '2020-05-17 02:43:20', 0),
(214, 22, 'salut', '2020-05-18 08:51:56', 0),
(215, 35, 'Salut', '2020-05-19 18:14:48', 0),
(216, 8, 'Yo', '2020-05-19 18:16:33', 1),
(217, 35, 'hehe', '2020-05-19 18:24:45', 1);

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `sid` char(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dateSession` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`sid`, `data`, `dateSession`) VALUES
('0622b49d78fb6d459c9c0d63059fa5ba', '', '2020-05-22 10:06:59'),
('12a20bf52155e47b2e36f8b1955bc3f5', '', '2020-05-22 10:26:58'),
('1e2dac734899e5f793ecb8c1f84dc6ac', '', '2020-05-22 07:15:34'),
('303fc278984f30f6a69cec6e62925b87', '', '2020-05-22 10:26:57'),
('36d7f7b0b260babb766db566219f736c', '', '2020-05-22 07:15:34'),
('6bcb475105ba35dc384dcbcb1e2e68a7', '', '2020-05-22 07:15:36'),
('81aace587e6b7baf199f18d8e8d1aec1', '', '2020-05-22 07:15:35'),
('8b7f200ebd967bf5c1287c2f732b9997', '', '2020-05-22 07:15:35'),
('9135113927f8a8a9dbaa5f91cb3d947c', '', '2020-05-22 07:15:33'),
('a9bd75926f103072337567fb78a0d323', 'idUser|i:22;', '2020-05-21 23:12:39'),
('c8972545dc499f0e2f80dddba6fd6fd8', '', '2020-05-22 11:07:50'),
('fb05133626b717766a7bb550a554b033', '', '2020-05-22 07:15:35');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `role` tinyint(4) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `avatar` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restoreCode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `timeRestore` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`idUser`, `email`, `password`, `role`, `name`, `score`, `avatar`, `restoreCode`, `timeRestore`) VALUES
(8, 'gregory.thorel@live.fr', '$2y$10$gxIrZpR8Qk/hfG2ywvcvm.LR0a1ShHRduBpXOYEh7LoaAqZyoDD0G', 1, 'Greg', 2885, '19', '5ec51bcbb0608', '2020-05-21 14:00:00'),
(22, 'boap@boap.com', '$2y$10$BUs9C.DH99RkKK05/ALu8.A4V9VvmiPI62s4GIL29iu7.5.cU2ANy', 0, 'Boap', 2930, '11', '5ec04bc71f675', '2020-05-17 22:23:00'),
(32, 'test@test.com', '$2y$10$EMEFwpsLN0yAeB1G7lYScOitHs6r5oOc8yAVnys.USAaXc60wuOKS', 0, 'RD2D2', 0, '5', NULL, NULL),
(33, 'lilya@labrune.com', '$2y$10$Gw/Y9kcCPqxrItQ3pN6Ze.bsPTXr3FH/r1LHK3KdhIPJcN1ghlE0q', 0, 'Gamer33', 0, '1', NULL, NULL),
(34, 'gregorythorel1@gmail.com', '$2y$10$Z1tSDYVWwKYEQE4GZkj0.efJwqEouR2xmAyWlKfaOpdvjj2iTOD1m', 0, 'Gamer34', 0, '1', '5ec051be5f5c5', '2020-05-17 22:49:00'),
(35, 'lordlannowar@hotmail.com', '$2y$10$GzgJkaNnS5d34pnkL6P8XeLwNSvo7Z4F/bc6GHvEA3OMgjfUjgDZC', 0, 'Gamer35', 0, '1', NULL, NULL),
(36, 'test@test.net', '$2y$10$cPm184YQuzV1ZQmQcdNCmO8eb/47pOS69RreBS3/0eslyvYoGQTYS', 0, 'Gamer36', 0, '1', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `battle`
--
ALTER TABLE `battle`
  ADD PRIMARY KEY (`idBattle`);

--
-- Indexes for table `boat`
--
ALTER TABLE `boat`
  ADD PRIMARY KEY (`idBoat`);

--
-- Indexes for table `launchBattle`
--
ALTER TABLE `launchBattle`
  ADD PRIMARY KEY (`idLaunchBattle`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`idMessage`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `battle`
--
ALTER TABLE `battle`
  MODIFY `idBattle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=916;

--
-- AUTO_INCREMENT for table `boat`
--
ALTER TABLE `boat`
  MODIFY `idBoat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1871;

--
-- AUTO_INCREMENT for table `launchBattle`
--
ALTER TABLE `launchBattle`
  MODIFY `idLaunchBattle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=612;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `idMessage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
