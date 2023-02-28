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
  `hospital` varchar(255) NOT NULL DEFAULT 'lsmc',
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
  `debutservice` datetime NOT NULL,
  `discord` varchar(255) DEFAULT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `sexe` varchar(10) NOT NULL DEFAULT 'H',
  `dateentreehopital` date DEFAULT NULL,
  `bank` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `effectif`
--

INSERT INTO `effectif` (`id`, `ip`, `pseudo`, `password`, `dateinscription`, `token`, `firstname`, `lastname`, `email`, `hospital`, `grade`, `role`, `agregation`, `phone`, `register`, `service`, `deservice`, `statut`, `intervention`, `commentaire`, `vehicule`, `debutservice`, `discord`, `uid`, `sexe`, `dateentreehopital`, `bank`) VALUES
(2, '::1', 'agassiwong', '$2y$12$6nVLRZs/3JQwDWOnvo1q1eqQydfBlQ.IeP3p8dXxDwV.eLOIjziua', '2023-02-22 00:08:10', 'cfe4765b400743637889fd45c9e61de1083c6b055567aa9925a653bd5920817f3a95dd913019c403d93860c930dfe08a3860a1d285d9cb6dea49e873d0486a8e', 'agassi', 'wong', 'agassi.wong@lsmc.com', 'lsmc', 'Ambulancier', 'Mentor', '', '55502128', 1, 1, 1, '6', 'Code 6', '', 'Aucun', '2023-02-28 15:02:08', NULL, NULL, 'H', NULL, ''),
(5, '2001:861:5d00:4f60:2', 'jonathanbot', '$2y$12$BFAdbLvOrFs7FVu.stTeq.FgFMd1NtU9kGCXXjQ5bRNj3q7OkXtrK', '2023-02-27 14:53:48', '711b6e5c7c37f656e4425b7779c941982dd86f78efd40faade262c9d564d982041541465b5fdfb112ccac6b568aa876e108fec579282ef643922ab628461fcea', 'jonathan', 'bot', 'jonathan.bot@lsmc.com', 'lsmc', 'Directeur', '', '', '55522333', 1, 0, 1, '', '', '', '', '0000-00-00 00:00:00', NULL, NULL, 'H', NULL, ''),
(6, '149.34.245.81', 'martinalexandrov', '$2y$12$rwfCEfjz0Uiiz60us8N7JeNMzAlVr2SsIb40RtZeHzLDKMHcdmHza', '2023-02-27 15:53:22', '57de9d38695f53509fc4019d1ead1b517103e4be175c2ff91dcf512620472ec17c97ebdc852f5ed7961e2ffdcf338cb592bb5055744473374d8a8d1ff67c7e26', 'martin', 'alexandrov', 'martin.alexandrov.lsmc@gmail.com', 'lsmc', 'Chef de Service', 'Mentor, Instructeur Medical, Responsable MARU', 'ASG MRG MARU', '55530531', 1, 0, 1, '', '', '', '', '0000-00-00 00:00:00', NULL, NULL, 'H', NULL, ''),
(10, '2001:861:5d00:4f60:c', 'bertrandfaure', '$2y$12$039TnOLV70QPH2bDEf66demuYYga6cDvDd4Dnt6Exc/mV2OVaSu0.', '2023-02-28 14:44:07', '087a29fa7197ee30f19de0d24fa4d8d9afb0c7ab77c8a20e538b10473184e4dc8914527c7ca0998e77773410fea6f7eddea5a17590a33e0fe323dcb7500e9946', 'bertrand', 'faure', '', 'lsmc', 'Directeur-Adjoint', 'Instructeur MÃ©dical', 'MARU', '55500179', 1, 0, 1, '', '', '', '', '0000-00-00 00:00:00', 'Onedrunkman#0001', '64892', 'H', NULL, '169268'),
(11, '176.147.213.69', 'tonypark', '$2y$12$uCb0EXzqscBoqeu8d3ACMuk9g1.BUvG3YEG6.gDNiUi0RZIYv9jTq', '2023-02-28 14:50:54', 'd2c05b3b1c2ce4a255f54033ea466893e863b78b7e384ea50c5774bba4ae2e31ffedddaa39fa75df7052f23e0c4840d9c62ebbcd5ffdb3a3307441c8c12b11b8', 'tony', 'park', '', 'lsmc', 'Chirurgien SpÃ©cialisÃ©', 'Instructeur MÃ©dical', 'MTT', '55566211', 1, 0, 0, '', '', '', '', '0000-00-00 00:00:00', '435440061358669826', '62041', 'H', NULL, '166211'),
(12, '2001:861:5d00:4f60:c', 'agassitestwongtest', '$2y$12$dOny7EEQyGcni9lDf/oocu3RdVb/y8.aWPLJOlVrrvoYXQPhu/YIS', '2023-02-28 15:12:16', '44f774ba858e2a4dd28f5a8054a1d372eafff7eca60d1d4cdcad5e3ef61ce68b1ecdf0f55361ea23f934a7faffffaf552ab2811680f3bdf80d98b230d0f0cfbe', 'agassitest', 'wongtest', '', 'lsmc', 'Externe', 'Mentor', 'MTT', '55502128', 1, 0, 1, '', '', '', '', '0000-00-00 00:00:00', 'testing', '111', 'H', NULL, '222'),
(13, '176.147.213.69', 'jordanlebeaux', '$2y$12$kT9nu3hF9jVbITd9scnZweiwY0vnyLNq.ppjwIwR75yyiTYnuJEam', '2023-02-28 16:02:55', 'c6657cd512939d2bca3daa8ae4805cea09d3bad93a3eb3810f12d106953f75809f0633f8a82e4a6aa0e0f667104a6770920859a81d8f5c4565c03dbae0585824', 'jordan', 'lebeaux', '', 'lsmc', 'Directeur-Adjoint', 'Instructeur MÃ©dical', 'ASG', '55596719', 1, 0, 1, '', '', '', '', '0000-00-00 00:00:00', '832027493690245150', '54120', 'H', NULL, '174778'),
(14, '176.147.213.69', 'greedvinsmoke', '$2y$12$za1OJ/ALVhDFHS1yXLwdeetBjI0ehIJmDusp5YPqjEEUefL3dZIAO', '2023-02-28 16:05:26', 'd557cad20caa84d2a288d0e0124427c188de8aab4c772d6a58ee6d258e4f7e8341126be417b872aa018b91112a4a4e7d75fbad5a7afbe3fc9664206462acacbd', 'greed', 'vinsmoke', '', 'lsmc', 'Directeur de Centre', 'Instructeur MÃ©dical', 'Tech ASG', '55531258', 1, 0, 1, '', '', '', '', '0000-00-00 00:00:00', '330054560833863683', '29603', 'H', NULL, '131258');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
