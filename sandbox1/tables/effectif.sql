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
-- Structure de la table `effectif`
--

CREATE TABLE `effectif` (
  `id` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `dateinscription` datetime NOT NULL DEFAULT current_timestamp(),
  `token` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `hospital` varchar(255) NOT NULL,
  `grade` varchar(255) NOT NULL DEFAULT '''Étudiant''',
  `role` varchar(255) NOT NULL,
  `agregation` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `register` tinyint(1) NOT NULL DEFAULT 0,
  `service` tinyint(1) NOT NULL DEFAULT 0,
  `statut` varchar(255) NOT NULL,
  `intervention` varchar(255) NOT NULL,
  `commentaire` varchar(255) NOT NULL,
  `vehicule` varchar(255) NOT NULL,
  `debutservice` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `effectif`
--

INSERT INTO `effectif` (`id`, `ip`, `pseudo`, `password`, `dateinscription`, `token`, `firstname`, `lastname`, `email`, `hospital`, `grade`, `role`, `agregation`, `phone`, `register`, `service`, `statut`, `intervention`, `commentaire`, `vehicule`, `debutservice`) VALUES
(2, '::1', 'agassiwong', '$2y$12$IwkxippI/RRxwJ4YGWK9/.arfKerapKr5eP.u3VYQoNe1cddRD0mW', '2023-02-22 00:08:10', 'cfe4765b400743637889fd45c9e61de1083c6b055567aa9925a653bd5920817f3a95dd913019c403d93860c930dfe08a3860a1d285d9cb6dea49e873d0486a8e', 'agassi', 'wong', 'agassi.wong@lsmc.com', 'lsmc', 'Ambulancier', 'Mentor', '', '555502128', 1, 1, '6', 'Code 6', '', 'Aucune', '2023-02-24 22:29:55'),
(3, '::1', 'coleanderson', '$2y$12$IwkxippI/RRxwJ4YGWK9/.arfKerapKr5eP.u3VYQoNe1cddRD0mW', '2023-02-23 00:08:10', 'cfe4765b400743637889fd45c9e61de1083c6b055567aa9925a653bd5920817f3a95dd913019c403d93860c930dfe08a3860a1d285d9cb6dea49e873d0486a8e', 'cole', 'anderson', 'cole.anderson@lsmc.com', 'lsmc', 'Interne', 'Mentor', '', '555555555', 1, 1, '0', 'Code 6', 'Code 6 - Unité Sauvage', '', '2023-02-22 23:21:21');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
