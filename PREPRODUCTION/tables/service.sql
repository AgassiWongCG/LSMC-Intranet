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
-- Structure de la table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `effectif` int(11) NOT NULL,
  `debutservice` datetime DEFAULT NULL,
  `finservice` datetime DEFAULT NULL,
  `jour` int(11) NOT NULL DEFAULT '0',
  `heure` int(11) NOT NULL DEFAULT '0',
  `minute` int(11) NOT NULL DEFAULT '0',
  `seconde` int(11) NOT NULL DEFAULT '0',
  `total` datetime DEFAULT NULL,
  `dernier` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `service`
--

INSERT INTO `service` (`id`, `effectif`, `debutservice`, `finservice`, `jour`, `heure`, `minute`, `seconde`, `total`, `dernier`) VALUES
(64, 2, '2023-02-27 14:42:49', '2023-02-27 14:42:52', 0, 0, 0, 3, NULL, 0),
(65, 2, '2023-02-27 14:42:54', '2023-02-27 14:42:56', 0, 0, 0, 2, NULL, 0),
(66, 2, '2023-02-27 14:42:59', '2023-02-27 14:43:00', 0, 0, 0, 1, NULL, 0),
(67, 2, '2023-02-27 14:43:03', '2023-02-27 14:43:06', 0, 0, 0, 3, NULL, 0),
(68, 2, '2023-02-27 14:45:00', '2023-02-27 14:45:01', 0, 0, 0, 1, NULL, 0),
(69, 2, '2023-02-27 14:46:33', '2023-02-27 14:46:35', 0, 0, 0, 2, NULL, 0),
(70, 2, '2023-02-27 14:46:37', '2023-02-27 14:46:40', 0, 0, 0, 3, NULL, 0),
(71, 2, '2023-02-27 14:57:38', '2023-02-27 14:58:46', 0, 0, 1, 8, NULL, 0),
(72, 2, '2023-02-27 14:58:48', NULL, 0, 0, 0, 0, NULL, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
