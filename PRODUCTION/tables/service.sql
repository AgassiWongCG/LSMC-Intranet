-- phpMyAdmin SQL Dump
-- version OVH
-- https://www.phpmyadmin.net/
--
-- Hôte : lsmcovptsg.mysql.db
-- Généré le : sam. 04 mars 2023 à 20:09
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
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `debutservice` datetime DEFAULT NULL,
  `finservice` datetime DEFAULT NULL,
  `jour` int(11) NOT NULL DEFAULT '0',
  `heure` int(11) NOT NULL DEFAULT '0',
  `minute` int(11) NOT NULL DEFAULT '0',
  `seconde` int(11) NOT NULL DEFAULT '0',
  `total` datetime DEFAULT NULL,
  `dernier` tinyint(1) NOT NULL,
  `past` tinyint(1) NOT NULL DEFAULT '0',
  `commentaire` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `service`
--

INSERT INTO `service` (`id`, `effectif`, `firstname`, `lastname`, `debutservice`, `finservice`, `jour`, `heure`, `minute`, `seconde`, `total`, `dernier`, `past`, `commentaire`, `status`) VALUES
(303, 2, 'agassi', 'wong', '2023-03-04 19:12:41', '2023-03-04 19:12:51', 0, 0, 0, 10, NULL, 0, 0, NULL, 'Code 7'),
(304, 2, 'agassi', 'wong', '2023-03-04 19:13:17', '2023-03-04 19:13:35', 0, 0, 0, 18, NULL, 0, 0, NULL, 'Code 3'),
(305, 2, 'Agassi', 'Wong', '2023-03-04 19:13:35', '2023-03-04 19:14:21', 0, 0, 0, 46, NULL, 0, 0, NULL, 'Code 6'),
(306, 2, 'agassi', 'wong', '2023-03-04 19:14:25', '2023-03-04 19:14:28', 0, 0, 0, 3, NULL, 0, 0, NULL, 'Code 6'),
(307, 31, 'martin', 'alexandrov', '2023-03-04 19:14:40', '2023-03-04 19:14:54', 0, 0, 0, 14, NULL, 0, 0, NULL, 'Code 6'),
(308, 31, 'Martin', 'Alexandrov', '2023-03-04 19:14:54', '2023-03-04 19:14:54', 0, 0, 0, 0, NULL, 0, 0, NULL, 'Code 7'),
(309, 2, 'agassi', 'wong', '2023-03-04 19:16:13', '2023-03-04 19:16:51', 0, 0, 0, 38, NULL, 0, 0, NULL, 'Code 6'),
(310, 2, 'agassi', 'wong', '2023-03-04 19:16:59', '2023-03-04 19:17:03', 0, 0, 0, 4, NULL, 0, 0, NULL, 'Formation'),
(311, 31, 'martin', 'alexandrov', '2023-03-04 19:17:03', '2023-03-04 19:17:11', 0, 0, 0, 8, NULL, 0, 0, NULL, 'Code 6'),
(312, 2, 'Agassi', 'Wong', '2023-03-04 19:17:03', '2023-03-04 19:25:39', 0, 0, 8, 36, NULL, 0, 0, NULL, 'Code 6'),
(313, 31, 'Martin', 'Alexandrov', '2023-03-04 19:17:11', '2023-03-04 19:19:07', 0, 0, 1, 56, NULL, 0, 0, NULL, 'Morgue'),
(314, 31, 'martin', 'alexandrov', '2023-03-04 19:22:39', '2023-03-04 19:22:57', 0, 0, 0, 18, NULL, 0, 0, NULL, 'Code 6'),
(315, 31, 'Martin', 'Alexandrov', '2023-03-04 19:22:57', '2023-03-04 19:23:04', 0, 0, 0, 7, NULL, 0, 0, NULL, 'Code 6'),
(316, 31, 'Martin', 'Alexandrov', '2023-03-04 19:23:04', '2023-03-04 19:24:23', 0, 0, 1, 19, NULL, 0, 0, NULL, 'Code 3'),
(317, 2, 'agassi', 'wong', '2023-03-04 19:28:05', '2023-03-04 19:28:17', 0, 0, 0, 11, NULL, 0, 0, NULL, 'Code 6'),
(318, 2, 'agassi', 'wong', '2023-03-04 19:49:57', '2023-03-04 19:50:03', 0, 0, 0, 6, NULL, 0, 0, NULL, 'Code 6'),
(319, 2, 'Agassi', 'Wong', '2023-03-04 19:50:03', NULL, 0, 0, 0, 0, NULL, 1, 0, NULL, 'Gestion Direction');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=320;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
