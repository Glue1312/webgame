-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2022 at 11:39 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pa_web`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `total_penghasilan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `password`, `total_penghasilan`) VALUES
('admin', 'admin', 4645999);

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE `game` (
  `id_game` int(11) NOT NULL,
  `nama_game` varchar(100) NOT NULL,
  `nama_dev` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `genre_1` varchar(30) NOT NULL,
  `genre_2` varchar(30) NOT NULL,
  `genre_3` varchar(30) NOT NULL,
  `spek` varchar(100) NOT NULL,
  `tanggal_rilis` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`id_game`, `nama_game`, `nama_dev`, `harga`, `genre_1`, `genre_2`, `genre_3`, `spek`, `tanggal_rilis`) VALUES
(1, 'Sekiro™: Shadows Die Twice - GOTY Edition', 'FromSoftware', 750000, 'fun', 'horror', ' git gud', 'Intel Core i3-2100, Ram 4GB, GTX 760', '2019-03-22'),
(2, 'Dark Souls', 'Fromsoftware', 500000, 'fun', 'horror', ' git gud', 'ram 8 gb, intel i5-11500H, RTX 4091', '2020-10-14'),
(3, 'Zelda', 'Nitendo', 500000, 'Open World', 'RPG', 'Story', 'RTX 3050', '0000-00-00'),
(4, 'Dead by Daylight', 'Behaviour Interactive', 135999, 'horror', 'action', 'horror', '8 GB RAM, GTX 460 1GB,50 GB', '2016-06-14'),
(26, 'Call of Duty®: Modern Warfare® II', 'Activision', 1000000, 'action', 'story', 'adventure', 'AMD Ryzen™ 3 1200, 8 GB RAM, GTX 960', '2022-10-28'),
(27, 'Dragon Age : Inquisition', 'BioWare', 115000, 'action', 'story', 'adventure', 'AMD Quad core @ 2.5 GHz, 4 GB RAM, AMD Radeon HD 4870', '2014-11-18'),
(28, 'The Witcher 3: Wild Hunt', 'CD PROJEKT RED', 369999, 'RPG', 'Open World', 'Fantasy', ' Intel CPU Core i5-2500K 3.3GHz, 6 GB RAM, GTX 660', '2015-05-18'),
(29, 'God of War', 'Santa Monica Studio', 729000, 'Action', 'Adventure', 'RPG', 'Intel i5-2500k (4 core 3.3 GHz), 8 GB RAM, GTX 960 (4 GB)', '2022-01-14'),
(31, 'It Takes Two', ' Hazelight', 191600, 'Action', 'Adventure', 'Multiplayer', ' Intel Core i3-2100T, 8GB RAM, GTX 660 ', '2021-03-26'),
(32, 'Elden Ring', 'FromSoftware Inc.', 599000, 'Action', 'RPG', 'Open World', 'INTEL CORE I5-8400, 12 GB RAM,  GTX 1060 3 GB', '2022-02-25'),
(33, 'The Elder Scrolls V: Skyrim Special Edition', ' Bethesda Game Studios', 329999, 'RPG', 'Adventure', 'SinglePlayer', 'Intel i5-750, 8 GB RAM, GTX 470 1GB', '2016-10-28');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_game` int(11) NOT NULL,
  `nama_game` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `tanggal_transaksi` datetime NOT NULL DEFAULT current_timestamp(),
  `key` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `id_game`, `nama_game`, `harga`, `tanggal_transaksi`, `key`) VALUES
(3, 5, 1, 'Sekiro', 10000, '2022-11-14 20:55:24', 'LGS3511'),
(7, 1, 2, 'Dark Soul', 500000, '2022-11-14 21:26:27', 'LGS712'),
(10, 4, 1, 'Sekiro', 750000, '2022-11-14 21:48:20', 'LGS10412'),
(14, 1, 3, 'Zelda', 500000, '2022-11-25 15:24:59', 'LGS1413'),
(15, 1, 1, 'Sekiro', 750000, '2022-11-25 15:45:58', 'LGS1511'),
(22, 16, 26, 'Call of Duty®: Modern Warfare® II', 1000000, '2022-11-28 00:03:33', 'LGS221626'),
(23, 16, 4, 'Dead by Daylight', 135999, '2022-11-28 00:06:10', 'LGS23164'),
(24, 20, 26, 'Call of Duty®: Modern Warfare® II', 1000000, '2022-11-28 20:15:41', 'LGS242026'),
(25, 5, 31, 'It Takes Two', 191600, '2022-11-29 17:36:01', 'LGS25531');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_telp` varchar(12) NOT NULL,
  `tanggal_dibuat` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `email`, `no_telp`, `tanggal_dibuat`) VALUES
(1, 'user', 'user', 'saga061003@gmail.com', '87978', '2022-11-14 19:19:21'),
(4, 'user2', 'user2', 'galang.satria06@yahoo.com', '87978', '2022-11-14 20:32:26'),
(5, 'silvester', 'silvester', 'silvester@gmail.com', '087123210093', '2022-11-23 23:06:12'),
(9, 'renanda', 'renanda', 'renanda@gmail.com', '087123210093', '2022-11-27 23:43:29'),
(15, 'renanda', 'renanda', 'renanda@gmail.com', '087123210093', '2022-11-27 23:57:49'),
(16, 'akun', 'akun', 'akun@akun.com', '087123210093', '2022-11-28 00:00:13'),
(17, 'akun', 'akun', 'akun@akun.com', '087123210093', '2022-11-28 00:00:33'),
(18, 'akun', 'akun', 'akun@akun.com', '087123210093', '2022-11-28 00:02:24'),
(19, 'Himawantoro', 'himawantoro', 'Himawantoro@gmail.com', '087123210093', '2022-11-28 20:01:34'),
(20, 'glue', 'glue', 'glue@gmail.com', '08123210093', '2022-11-28 20:06:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`id_game`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_game` (`id_game`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `id_game` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
