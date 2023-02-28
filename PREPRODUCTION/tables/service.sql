-- phpMyAdmin SQL Dump
-- version OVH
-- https://www.phpmyadmin.net/
--
-- Hôte : lsmcovptsg.mysql.db
-- Généré le : mar. 28 fév. 2023 à 18:04
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
(72, 2, '2023-02-27 14:58:48', '2023-02-27 15:39:47', 0, 0, 40, 59, NULL, 0),
(73, 2, '2023-02-27 15:39:48', '2023-02-27 15:41:51', 0, 0, 2, 3, NULL, 0),
(74, 2, '2023-02-27 15:41:54', '2023-02-27 20:12:57', 0, 4, 31, 3, NULL, 0),
(75, 5, '2023-02-27 15:50:01', '2023-02-27 16:02:06', 0, 0, 12, 5, NULL, 0),
(76, 6, '2023-02-27 15:55:04', '2023-02-27 15:55:08', 0, 0, 0, 4, NULL, 0),
(77, 6, '2023-02-27 16:03:09', '2023-02-27 16:04:39', 0, 0, 1, 30, NULL, 0),
(78, 5, '2023-02-27 16:03:47', '2023-02-27 17:11:25', 0, 1, 7, 38, NULL, 0),
(79, 6, '2023-02-27 16:04:44', '2023-02-27 16:06:41', 0, 0, 1, 57, NULL, 0),
(80, 6, '2023-02-27 16:07:16', '2023-02-27 16:21:18', 0, 0, 14, 2, NULL, 0),
(81, 6, '2023-02-27 16:44:48', '2023-02-27 16:50:54', 0, 0, 6, 6, NULL, 0),
(82, 6, '2023-02-27 20:24:23', '2023-02-27 20:24:26', 0, 0, 0, 3, NULL, 0),
(83, 2, '2023-02-27 21:01:07', '2023-02-27 21:06:52', 0, 0, 5, 45, NULL, 0),
(84, 5, '2023-02-27 21:06:45', '2023-02-28 10:10:59', 0, 13, 4, 14, NULL, 0),
(85, 5, '2023-02-28 10:11:10', '2023-02-28 10:44:24', 0, 0, 33, 14, NULL, 0),
(86, 2, '2023-02-28 10:26:05', '2023-02-28 13:17:29', 0, 2, 51, 24, NULL, 0),
(87, 6, '2023-02-28 13:54:28', '2023-02-28 14:09:37', 0, 0, 15, 9, NULL, 0),
(88, 10, '2023-02-28 14:46:24', '2023-02-28 14:46:27', 0, 0, 0, 3, NULL, 0),
(89, 2, '2023-02-28 14:55:00', '2023-02-28 15:01:33', 0, 0, 6, 33, NULL, 0),
(90, 10, '2023-02-28 14:55:53', '2023-02-28 15:10:35', 0, 0, 14, 42, NULL, 0),
(91, 2, '2023-02-28 15:02:08', NULL, 0, 0, 0, 0, NULL, 1),
(92, 10, '2023-02-28 16:06:03', '2023-02-28 16:06:59', 0, 0, 0, 56, NULL, 0),
(93, 13, '2023-02-28 16:06:18', '2023-02-28 16:07:24', 0, 0, 1, 6, NULL, 0),
(94, 6, '2023-02-28 17:53:39', '2023-02-28 17:53:54', 0, 0, 0, 15, NULL, 0),
(95, 6, '2023-02-28 17:57:43', '2023-02-28 18:00:32', 0, 0, 2, 49, NULL, 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
