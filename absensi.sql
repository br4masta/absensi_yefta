-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Jun 2024 pada 17.47
-- Versi server: 10.4.22-MariaDB
-- Versi PHP: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id_absen` int(11) NOT NULL,
  `tgl` date NOT NULL,
  `waktu` time NOT NULL,
  `keterangan` enum('Masuk','Pulang') NOT NULL,
  `id_user` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `capture_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `jenis` int(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `absensi`
--

INSERT INTO `absensi` (`id_absen`, `tgl`, `waktu`, `keterangan`, `id_user`, `file_path`, `latitude`, `longitude`, `capture_time`, `jenis`) VALUES
(4, '2019-07-25', '07:21:53', 'Masuk', 6, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(5, '2019-07-26', '09:00:47', 'Masuk', 6, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(6, '2019-07-26', '16:01:03', 'Pulang', 6, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(7, '2019-07-25', '17:01:28', 'Pulang', 6, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(8, '2021-02-15', '11:15:15', 'Masuk', 13, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(9, '2021-02-15', '11:15:52', 'Pulang', 13, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(10, '2021-02-15', '11:16:29', 'Pulang', 13, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(11, '2021-02-15', '05:17:06', 'Pulang', 13, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(12, '2021-02-15', '11:59:04', 'Masuk', 16, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(13, '2021-02-15', '11:59:09', 'Pulang', 16, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(14, '2021-02-15', '11:59:21', 'Masuk', 17, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(16, '2021-02-15', '11:59:38', 'Masuk', 18, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(17, '2021-02-15', '11:59:43', 'Pulang', 18, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(18, '2021-02-15', '11:59:56', 'Masuk', 19, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(19, '2021-02-15', '12:00:00', 'Pulang', 19, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(20, '2021-02-15', '12:04:36', 'Masuk', 20, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(21, '2021-02-15', '12:04:45', 'Pulang', 20, '', '0.00000000', '0.00000000', '2024-06-15 13:45:48', 0),
(40, '2024-06-15', '23:53:26', 'Masuk', 16, 'uploads/snapshot_666dc70617de1.png', '-7.31054080', '112.72847360', '2024-06-15 16:53:26', 0),
(41, '2024-06-15', '23:55:35', 'Pulang', 16, '', '-7.31054080', '112.72847360', '2024-06-15 16:55:35', 1),
(45, '2024-06-16', '14:07:24', 'Masuk', 16, 'uploads/snapshot_666e8f2c96884.png', '-7.31709440', '112.74485760', '2024-06-16 07:07:24', 0),
(46, '2024-06-16', '14:07:41', 'Pulang', 16, 'uploads/snapshot_666e8f3d51349.png', '-7.31709440', '112.74485760', '2024-06-16 07:07:41', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `divisi`
--

CREATE TABLE `divisi` (
  `id_divisi` smallint(3) NOT NULL,
  `nama_divisi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `divisi`
--

INSERT INTO `divisi` (`id_divisi`, `nama_divisi`) VALUES
(1, 'Marketing'),
(2, 'IT '),
(3, 'PPIC'),
(6, 'HRGA'),
(7, 'Kebersihan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jam`
--

CREATE TABLE `jam` (
  `id_jam` tinyint(1) NOT NULL,
  `start` time NOT NULL,
  `finish` time NOT NULL,
  `keterangan` enum('Masuk','Pulang') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `jam`
--

INSERT INTO `jam` (`id_jam`, `start`, `finish`, `keterangan`) VALUES
(1, '07:00:00', '07:20:00', 'Masuk'),
(2, '17:00:00', '22:00:00', 'Pulang');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
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
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nik`, `nama`, `telp`, `email`, `foto`, `divisi`, `username`, `password`, `level`) VALUES
(1, '1234567890', 'Akang Admin', '087756676788', 'admin@gmail.com', '1612584514.png', NULL, 'admin', '$2y$10$i/RfCV9iyMOEf9FAcLaSzuMc3QBszq.RtGpTd2Qn7T8rSkwcOsvRC', 'Manager'),
(16, '1001', 'Brian cek cek', '087756676788', 'karyawan1@gmail.com', '1718522315.jpg', 1, 'karyawan1', '$2y$10$iIxzFnrButayTQvCeg77AueoU.0Hq58fAFPJ40HMYYOoOZzDAqEAa', 'Karyawan'),
(17, '1002', 'Nama Karyawan2', '087756676700', 'karyawan2@gmail.com', 'no-foto.png', 2, 'karyawan2', '$2y$10$qaQpw24aqrZz4cSjzEPWHuP8zkjv4SR1.BH/4l/ZkiCZHu1mhqoDq', 'Karyawan'),
(18, '1003', 'Nama Karyawan3', '087756676789', 'karyawan3@gmail.com', 'no-foto.png', 3, 'karyawan3', '$2y$10$Xpq9pc.e8ycGdUpKwxNgIedtHCsiwvuOQQEV9Kyxl40Usw3mE/XnS', 'Karyawan'),
(19, '1004', 'Nama Karyawan4', '087756676787', 'karyawan4@gmail.com', 'no-foto.png', 6, 'karyawan4', '$2y$10$bF4HGhCdVYlm4B.yeHqWr.pl4dE7stIrAeYejnHQySD8kLxdsZx0O', 'Karyawan'),
(20, '1005', 'Nama Karyawan5', '087756676786', 'karyawan5@gmail.com', 'no-foto.png', 7, 'karyawan5', '$2y$10$Q9onBPMyjie41QlvnvlyCuzSrFCpWBNU4Cfh2Pd2VvnOTMr1sbuHe', 'Karyawan');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absen`);

--
-- Indeks untuk tabel `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id_divisi`);

--
-- Indeks untuk tabel `jam`
--
ALTER TABLE `jam`
  ADD PRIMARY KEY (`id_jam`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id_divisi` smallint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `jam`
--
ALTER TABLE `jam`
  MODIFY `id_jam` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` smallint(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
