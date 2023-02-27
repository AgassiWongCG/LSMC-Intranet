-- phpMyAdmin SQL Dump
-- version OVH
-- https://www.phpmyadmin.net/
--
-- Hôte : lsmcovptsg.mysql.db
-- Généré le : lun. 27 fév. 2023 à 15:03
-- Version du serveur : 5.7.41-log
-- Version de PHP : 7.4.29

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
-- Structure de la table `effectif`
--

CREATE TABLE `effectif` (
  `id` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `dateinscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `token` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `hospital` varchar(255) NOT NULL DEFAULT 'lsmcovptsg',
  `grade` varchar(255) NOT NULL DEFAULT 'Étudiant',
  `role` varchar(255) NOT NULL,
  `agregation` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `register` tinyint(1) NOT NULL DEFAULT '0',
  `service` tinyint(1) NOT NULL DEFAULT '0',
  `deservice` tinyint(1) NOT NULL DEFAULT '0',
  `statut` varchar(255) NOT NULL,
  `intervention` varchar(255) NOT NULL,
  `commentaire` varchar(255) NOT NULL,
  `vehicule` varchar(255) NOT NULL,
  `debutservice` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `effectif`
--

INSERT INTO `effectif` (`id`, `ip`, `pseudo`, `password`, `dateinscription`, `token`, `firstname`, `lastname`, `email`, `hospital`, `grade`, `role`, `agregation`, `phone`, `register`, `service`, `deservice`, `statut`, `intervention`, `commentaire`, `vehicule`, `debutservice`) VALUES
(2, '::1', 'agassiwong', '$2y$12$6Q3mVyUsL/2rb625WoWjUuBF5CZITbT0QIEsW2zuGIhOJGkFZDu32', '2023-02-22 00:08:10', 'cfe4765b400743637889fd45c9e61de1083c6b055567aa9925a653bd5920817f3a95dd913019c403d93860c930dfe08a3860a1d285d9cb6dea49e873d0486a8e', 'agassi', 'wong', 'agassi.wong@lsmc.com', 'lsmc', 'Ambulancier', 'Mentor', '', '555502128', 1, 1, 1, '6', 'Code 6', '', 'Aucun', '2023-02-27 14:58:48'),
(5, '2001:861:5d00:4f60:2', 'jonathanbot', '$2y$12$BFAdbLvOrFs7FVu.stTeq.FgFMd1NtU9kGCXXjQ5bRNj3q7OkXtrK', '2023-02-27 14:53:48', '711b6e5c7c37f656e4425b7779c941982dd86f78efd40faade262c9d564d982041541465b5fdfb112ccac6b568aa876e108fec579282ef643922ab628461fcea', 'jonathan', 'bot', 'jonathan.bot@lsmc.com', 'lsmcovptsg', 'Directeur', '', '', '55522333', 1, 0, 1, '', '', '', '', '0000-00-00 00:00:00');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `effectif`
--
ALTER TABLE `effectif`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `effectif`
--
ALTER TABLE `effectif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
