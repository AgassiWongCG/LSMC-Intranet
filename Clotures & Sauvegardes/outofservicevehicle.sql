-- phpMyAdmin SQL Dump
-- version OVH
-- https://www.phpmyadmin.net/
--
-- Hôte : lsmcovptsg.mysql.db
-- Généré le : dim. 02 avr. 2023 à 00:11
-- Version du serveur : 5.7.41-log
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `lsmcovptsg`
--

-- --------------------------------------------------------

--
-- Structure de la table `outofservicevehicle`
--

CREATE TABLE `outofservicevehicle` (
  `id` int(11) NOT NULL,
  `effectifid` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `vehicle` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `datereport` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `past` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `outofservicevehicle`
--

INSERT INTO `outofservicevehicle` (`id`, `effectifid`, `firstname`, `lastname`, `vehicle`, `company`, `datereport`, `past`) VALUES
(9, '129', 'ferhat', 'aslan', 'Ambulance 1', 'Los Santos Custom', '2023-03-15 23:44:33', 0),
(10, '31', 'martin', 'alexandrov', 'Vapid', 'Los Santos Custom', '2023-03-18 09:58:44', 0),
(11, '122', 'joseph', 'pablo', 'Ambulance 1', 'Los Santos Custom', '2023-03-20 20:11:35', 0),
(12, '31', 'martin', 'alexandrov', 'Alamo', 'Los Santos Custom', '2023-03-25 16:30:40', 0),
(13, '69', 'julian', 'barillas', 'Vapid', 'Los Santos Custom', '2023-03-26 19:38:49', 0),
(14, '82', 'manor', 'johnson', 'Vapid', 'Los Santos Custom', '2023-03-29 01:20:32', 0),
(15, '122', 'joseph', 'pablo', 'Ambulance 1', 'Los Santos Custom', '2023-03-30 20:04:02', 0),
(16, '122', 'joseph', 'pablo', 'Ambulance 1', 'Los Santos Custom', '2023-03-31 22:02:54', 0),
(17, '80', 'isaac', 'yee', 'HÃ©licoptÃ¨re', 'Los Santos Custom', '2023-03-31 22:36:29', 0),
(18, '31', 'martin', 'alexandrov', 'Vapid', 'Los Santos Custom', '2023-04-01 20:52:30', 0),
(19, '158', 'john', 'wish', 'Ambulance 1', 'Los Santos Custom', '2023-04-01 21:46:24', 0),
(20, '122', 'joseph', 'pablo', 'Ambulance 1', 'Los Santos Custom', '2023-04-01 21:46:24', 0),
(21, '122', 'joseph', 'pablo', 'Ambulance 1', 'Los Santos Custom', '2023-04-01 22:15:10', 0),
(22, '158', 'john', 'wish', 'Ambulance 1', 'Los Santos Custom', '2023-04-01 22:15:13', 0),
(23, '69', 'julian', 'barillas', 'Ambulance 1', 'Los Santos Custom', '2023-04-01 22:35:07', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `outofservicevehicle`
--
ALTER TABLE `outofservicevehicle`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `outofservicevehicle`
--
ALTER TABLE `outofservicevehicle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
