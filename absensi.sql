-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Jun 2024 pada 18.58
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
(41, '2024-06-15', '23:55:35', 'Pulang', 16, '', '-7.31054080', '112.72847360', '2024-06-15 16:55:35', 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absen`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
