-- phpMyAdmin SQL Dump
-- version OVH
-- https://www.phpmyadmin.net/
--
-- Hôte : lsmcovptsg.mysql.db
-- Généré le : dim. 19 mars 2023 à 09:39
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
(10, '31', 'martin', 'alexandrov', 'Vapid', 'Los Santos Custom', '2023-03-18 09:58:44', 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
