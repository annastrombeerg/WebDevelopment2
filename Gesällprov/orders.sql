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
-- Tabellstruktur `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `message` varchar(50) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumpning av Data i tabell `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `message`, `order_date`) VALUES
(1, 1, 1, 3, NULL, '2025-04-23 11:27:11'),
(2, 1, 2, 1, NULL, '2025-04-23 11:27:11'),
(3, 1, 1, 3, NULL, '2025-04-23 11:46:45'),
(4, 1, 4, 2, NULL, '2025-04-23 11:46:45'),
(5, 2, 4, 6, NULL, '2025-04-23 12:12:32'),
(6, 3, 4, 1, NULL, '2025-04-23 13:07:14'),
(7, 3, 3, 2, NULL, '2025-04-23 13:07:14'),
(8, 1, 1, 1, NULL, '2025-04-23 13:10:49'),
(9, 2, 1, 1, NULL, '2025-04-29 09:42:10'),
(10, 2, 2, 1, NULL, '2025-04-29 09:42:10'),
(11, 2, 5, 3, NULL, '2025-04-29 09:56:54'),
(12, 2, 6, 1, NULL, '2025-04-29 09:56:54'),
(13, 3, 5, 8, 'Happy Birthday Martin', '2025-04-29 10:25:28'),
(14, 4, 3, 3, 'Congratz Emmy', '2025-04-29 10:27:08'),
(15, 4, 4, 3, 'Congratz Emmy', '2025-04-29 10:27:08'),
(16, 4, 1, 10, 'Tea Party', '2025-04-29 10:58:23'),
(17, 4, 6, 6, 'Tea Party', '2025-04-29 10:58:23'),
(18, 4, 5, 10, 'Tea Party', '2025-04-29 10:58:23'),
(19, 4, 2, 6, 'Tea Party', '2025-04-29 10:58:23'),
(20, 1, 6, 20, 'LOVE YOU', '2025-04-29 11:34:06'),
(21, 1, 2, 10, 'Baby Shower', '2025-05-05 12:56:56'),
(22, 5, 3, 40, 'Kakor till barnen', '2025-05-07 18:52:21'),
(23, 1, 2, 5, 'Best Sister', '2025-05-09 12:31:12'),
(24, 4, 4, 15, 'Mint Condition', '2025-05-09 12:50:56');

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restriktioner för dumpade tabeller
--

--
-- Restriktioner för tabell `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
