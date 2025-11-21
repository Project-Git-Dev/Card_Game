-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 21 nov 2025 om 12:33
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `card_game`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `score` int(11) DEFAULT 0,
  `level` int(11) DEFAULT 1,
  `wins` int(11) DEFAULT 0,
  `games_played` int(11) DEFAULT 0,
  `cards_played` int(11) DEFAULT 0,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `score`, `level`, `wins`, `games_played`, `cards_played`, `profile_image`) VALUES
(24, '232', '$2y$10$Y8Z1x6o0tbXxglY60I4mL.6MzErwWe/fXv9DSeYtlYm.MZTH7e3Xe', '2025-04-13 22:13:03', 0, 1, 0, 0, 0, NULL),
(25, '23', '$2y$10$eAoKZuekk3sCtnK6QM2YTewIk3o7nvafjB3.1yMgNkBS/mUUMibS2', '2025-04-14 10:50:03', 0, 1, 0, 0, 0, NULL),
(26, '66', '$2y$10$qNNRI5pEC7JHnnAcaMNg1.jdeiByEkWWwgxAY.FIkdnqY1Z9tT4v6', '2025-04-14 11:10:32', 0, 1, 1, 7, 6, NULL),
(27, '556', '$2y$10$kKhmK2ZJ1SyMwOIgPCwlguZ24dL5I7Wy.pD1Trx19Ttg0lZ9ywTv.', '2025-04-15 07:12:53', 0, 1, 6, 23, 38, 'avatar4.png'),
(28, '11', '$2y$10$uYn8KeWW0rVhNP7vpvevN.ygvACJJA98aGqg9QslIqCnOrPyNgkwi', '2025-04-15 07:55:14', 0, 1, 9, 42, 47, 'avatar2.png'),
(29, '676', '$2y$10$9cZq1xp2vakwUPZTGA6ywePJONyRMLqpq7xuRQswW/8/dJaMYNF7W', '2025-04-15 08:36:49', 0, 1, 4, 5, 20, 'avatar2.png'),
(30, 'MystiekGeheimen', '$2y$10$/TcpbCagS/yNtm.z4iEtrO6x.pTZ94j4DfgApt98sMSN4dPR.LKd2', '2025-04-15 09:56:07', 0, 1, 0, 0, 0, NULL),
(31, '33', '$2y$10$7zwub1.OhU1u1LvQRQXRFeGvvghI6QAfYVqxwiG35B9ugBWlSQ9D6', '2025-04-15 09:56:35', 0, 1, 1, 1, 8, 'avatar1.png'),
(32, '34', '$2y$10$4an0jOOBJdBsnV6yQOXel.S01pRlseRoVnh8dAfUsxtQs2CXTmnvK', '2025-04-15 09:59:20', 210, 2, 0, 0, 3, NULL),
(33, '449', '$2y$10$CzRHJqC8pO3OGb4w75Cf6.T0XZgi3lTJP75L.FEos2kpXDvUj1/om', '2025-06-05 10:38:06', 0, 1, 0, 0, 0, NULL);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
