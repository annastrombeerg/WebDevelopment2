-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Värd: localhost
-- Tid vid skapande: 09 maj 2025 kl 14:59
-- Serverversion: 10.4.28-MariaDB
-- PHP-version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `E_Commerce_db`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumpning av Data i tabell `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$vcZC5cpZTo2Aom7T0VPhOuRlhvDMPB2Yt5TDiSS8TVoUIeFPRNnTq'),
(2, 'root', 'root@gmail.com', '$2y$10$uF7jTzZwEGVA7LlAUvzaE.eWK2W.IRgyVCdVAmWJSaPAudZIDJLmu'),
(3, 'katrin', 'katrin@gmail.com', '$2y$10$oJyavoz7LlhFwUUUqq9JiuEuoFCLqYoL8TbP13irn8bI3T0dAmx/a'),
(4, 'elida', 'elida@gmail.com', '$2y$10$SNz8HDngJugSTalAOhKI8e2jRBtzSL0ci86o.z1fBncGu6tdMqaHe'),
(5, 'theo', 'theo@gmail.com', '$2y$10$wgUHgRbQxRizBcvzJYaN9.O3Wjez.JS9xMiNRGvATR9xtI9uokMrS');

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
