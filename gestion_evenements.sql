-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 27, 2025 at 09:48 AM
-- Server version: 5.7.24
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gestion_evenements`
--

-- --------------------------------------------------------

--
-- Table structure for table `equipements`
--

CREATE TABLE `equipements` (
  `id_equipements` int(11) NOT NULL,
  `nom` enum('velo','surf','ski') NOT NULL,
  `disponibilite` varchar(128) NOT NULL,
  `prix` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `evenements`
--

CREATE TABLE `evenements` (
  `id_evenements` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `date_evenement` date NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inscr`
--

CREATE TABLE `inscr` (
  `id_inscription` int(11) NOT NULL,
  `id_utilisateurs` int(11) NOT NULL,
  `id_enenements` int(11) NOT NULL,
  `date_inscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('utilisateur','admin') NOT NULL DEFAULT 'utilisateur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `equipements`
--
ALTER TABLE `equipements`
  ADD PRIMARY KEY (`id_equipements`);

--
-- Indexes for table `evenements`
--
ALTER TABLE `evenements`
  ADD PRIMARY KEY (`id_evenements`);

--
-- Indexes for table `inscr`
--
ALTER TABLE `inscr`
  ADD PRIMARY KEY (`id_inscription`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `equipements`
--
ALTER TABLE `equipements`
  MODIFY `id_equipements` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evenements`
--
ALTER TABLE `evenements`
  MODIFY `id_evenements` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inscr`
--
ALTER TABLE `inscr`
  MODIFY `id_inscription` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
