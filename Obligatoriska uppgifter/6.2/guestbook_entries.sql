-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Värd: localhost
-- Tid vid skapande: 11 mars 2025 kl 18:06
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
-- Databas: `guestbook_db`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `guestbook_entries`
--

CREATE TABLE `guestbook_entries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `homepage` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumpning av Data i tabell `guestbook_entries`
--

INSERT INTO `guestbook_entries` (`id`, `name`, `email`, `homepage`, `comment`, `created_at`) VALUES
(8, 'Test', 'test@yahoo.se', 'test.se', 'Testar', '2025-03-11 16:51:12'),
(9, 'Test', 'test@yahoo.se', 'test.se', 'Test', '2025-03-11 16:51:50'),
(10, 'Test', 'test@yahoo.se', 'test.se', 'Test', '2025-03-11 16:52:53'),
(11, 'Customer', 'customer@hotmail.com', 'customerwebsite.com', 'Im a customer', '2025-03-11 16:53:21'),
(12, 'Customer', 'customer@hotmail.com', 'customerwebsite.com', 'Im a customer', '2025-03-11 16:54:08'),
(13, 'Customer', 'customer@hotmail.com', 'customerwebsite.com', 'Im a customer', '2025-03-11 16:56:05'),
(14, 'Theo', 'theo@email.com', 'theoswebsite.se', 'Im Theo!', '2025-03-11 16:56:26'),
(15, 'Develop', 'developer@now.com', 'idevelop.com', 'Im a developer!', '2025-03-11 16:58:57'),
(16, 'Develop', 'developer@now.com', 'idevelop.com', 'Im a developer!', '2025-03-11 17:00:43'),
(17, 'Liam', 'liam@hotmail.com', 'liam.now', 'Goodday!!', '2025-03-11 17:01:09');

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `guestbook_entries`
--
ALTER TABLE `guestbook_entries`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `guestbook_entries`
--
ALTER TABLE `guestbook_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
