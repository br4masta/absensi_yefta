-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2021 at 06:06 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absen` int(11) NOT NULL,
  `tgl` date NOT NULL,
  `waktu` time NOT NULL,
  `keterangan` enum('Masuk','Pulang') NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id_absen`, `tgl`, `waktu`, `keterangan`, `id_user`) VALUES
(4, '2019-07-25', '07:21:53', 'Masuk', 6),
(5, '2019-07-26', '09:00:47', 'Masuk', 6),
(6, '2019-07-26', '16:01:03', 'Pulang', 6),
(7, '2019-07-25', '17:01:28', 'Pulang', 6),
(8, '2021-02-15', '11:15:15', 'Masuk', 13),
(9, '2021-02-15', '11:15:52', 'Pulang', 13),
(10, '2021-02-15', '11:16:29', 'Pulang', 13),
(11, '2021-02-15', '05:17:06', 'Pulang', 13),
(12, '2021-02-15', '11:59:04', 'Masuk', 16),
(13, '2021-02-15', '11:59:09', 'Pulang', 16),
(14, '2021-02-15', '11:59:21', 'Masuk', 17),
(15, '2021-02-15', '11:59:24', 'Pulang', 17),
(16, '2021-02-15', '11:59:38', 'Masuk', 18),
(17, '2021-02-15', '11:59:43', 'Pulang', 18),
(18, '2021-02-15', '11:59:56', 'Masuk', 19),
(19, '2021-02-15', '12:00:00', 'Pulang', 19),
(20, '2021-02-15', '12:04:36', 'Masuk', 20),
(21, '2021-02-15', '12:04:45', 'Pulang', 20);

-- --------------------------------------------------------

--
-- Table structure for table `divisi`
--

CREATE TABLE `divisi` (
  `id_divisi` smallint(3) NOT NULL,
  `nama_divisi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `divisi`
--

INSERT INTO `divisi` (`id_divisi`, `nama_divisi`) VALUES
(1, 'Marketing'),
(2, 'IT '),
(3, 'PPIC'),
(6, 'HRGA'),
(7, 'Kebersihan');

-- --------------------------------------------------------

--
-- Table structure for table `jam`
--

CREATE TABLE `jam` (
  `id_jam` tinyint(1) NOT NULL,
  `start` time NOT NULL,
  `finish` time NOT NULL,
  `keterangan` enum('Masuk','Pulang') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jam`
--

INSERT INTO `jam` (`id_jam`, `start`, `finish`, `keterangan`) VALUES
(1, '07:00:00', '07:20:00', 'Masuk'),
(2, '16:00:00', '22:00:00', 'Pulang');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` smallint(5) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `telp` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `foto` varchar(20) DEFAULT 'no-foto.png',
  `divisi` smallint(5) DEFAULT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(60) NOT NULL,
  `level` enum('Manager','Karyawan') NOT NULL DEFAULT 'Karyawan'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nik`, `nama`, `telp`, `email`, `foto`, `divisi`, `username`, `password`, `level`) VALUES
(1, '1234567890', 'Akang Admin', '087756676788', 'admin@mail.com', '1612584514.png', NULL, 'admin', '$2y$10$i/RfCV9iyMOEf9FAcLaSzuMc3QBszq.RtGpTd2Qn7T8rSkwcOsvRC', 'Manager'),
(16, '1001', 'Nama Karyawan1', '087756676788', 'karyawan1@gmail.com', 'no-foto.png', 1, 'karyawan1', '$2y$10$fb40eADx8geJGnKfeLtS3eJ1fcbj94RJoi4oUjQQ2FXgfv6wt6pZW', 'Karyawan'),
(17, '1002', 'Nama Karyawan2', '087756676700', 'karyawan2@gmail.com', 'no-foto.png', 2, 'karyawan2', '$2y$10$qaQpw24aqrZz4cSjzEPWHuP8zkjv4SR1.BH/4l/ZkiCZHu1mhqoDq', 'Karyawan'),
(18, '1003', 'Nama Karyawan3', '087756676789', 'karyawan3@gmail.com', 'no-foto.png', 3, 'karyawan3', '$2y$10$Xpq9pc.e8ycGdUpKwxNgIedtHCsiwvuOQQEV9Kyxl40Usw3mE/XnS', 'Karyawan'),
(19, '1004', 'Nama Karyawan4', '087756676787', 'karyawan4@gmail.com', 'no-foto.png', 6, 'karyawan4', '$2y$10$bF4HGhCdVYlm4B.yeHqWr.pl4dE7stIrAeYejnHQySD8kLxdsZx0O', 'Karyawan'),
(20, '1005', 'Nama Karyawan5', '087756676786', 'karyawan5@gmail.com', 'no-foto.png', 7, 'karyawan5', '$2y$10$Q9onBPMyjie41QlvnvlyCuzSrFCpWBNU4Cfh2Pd2VvnOTMr1sbuHe', 'Karyawan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absen`);

--
-- Indexes for table `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id_divisi`);

--
-- Indexes for table `jam`
--
ALTER TABLE `jam`
  ADD PRIMARY KEY (`id_jam`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id_divisi` smallint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `jam`
--
ALTER TABLE `jam`
  MODIFY `id_jam` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` smallint(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
