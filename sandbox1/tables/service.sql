-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 24 fév. 2023 à 22:53
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `lsmc`
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
  `jour` int(11) NOT NULL DEFAULT 0,
  `heure` int(11) NOT NULL DEFAULT 0,
  `minute` int(11) NOT NULL DEFAULT 0,
  `seconde` int(11) NOT NULL DEFAULT 0,
  `total` datetime DEFAULT NULL,
  `dernier` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `service`
--

INSERT INTO `service` (`id`, `effectif`, `debutservice`, `finservice`, `jour`, `heure`, `minute`, `seconde`, `total`, `dernier`) VALUES
(27, 2, '2023-02-24 21:03:34', '2023-02-24 21:03:41', 0, 0, 0, 7, NULL, 0),
(28, 2, '2023-02-24 21:03:51', '2023-02-24 22:21:59', 0, 1, 18, 8, NULL, 0),
(29, 2, '2023-02-24 22:29:55', NULL, 0, 0, 0, 0, NULL, 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
