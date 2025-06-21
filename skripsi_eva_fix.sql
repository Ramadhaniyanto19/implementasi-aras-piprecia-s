-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Jun 2025 pada 05.14
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skripsi_eva`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bobot_kriteria`
--

CREATE TABLE `bobot_kriteria` (
  `id` int(11) NOT NULL,
  `kriteria` varchar(50) NOT NULL,
  `jenis` enum('benefit','cost') NOT NULL DEFAULT 'benefit',
  `rank_piprecia` int(11) DEFAULT NULL,
  `sj` float DEFAULT NULL,
  `kj` float DEFAULT NULL,
  `qj` float DEFAULT NULL,
  `bobot_piprecia` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `bobot_kriteria`
--

INSERT INTO `bobot_kriteria` (`id`, `kriteria`, `jenis`, `rank_piprecia`, `sj`, `kj`, `qj`, `bobot_piprecia`) VALUES
(25, 'Waktu Respon', 'cost', 1, 0, 1, 1, 0.57379),
(26, 'Ketersediaan Layanan', 'benefit', 2, 1.2, 2.2, 0.454545, 0.260814),
(27, 'Kepuasan Pengguna', 'benefit', 3, 1.4, 2.4, 0.189394, 0.108672),
(28, 'Biaya Layanan', 'cost', 4, 1.6, 2.6, 0.0728438, 0.041797),
(29, 'Kemudahan Aplikasi', 'benefit', 5, 1.8, 2.8, 0.0260157, 0.0149275);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_konversi`
--

CREATE TABLE `data_konversi` (
  `id` int(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `waktu_respon` decimal(10,2) DEFAULT NULL,
  `ketersediaan_layanan` decimal(10,2) DEFAULT NULL,
  `kepuasan_pengguna` decimal(10,2) DEFAULT NULL,
  `biaya_layanan` decimal(10,2) DEFAULT NULL,
  `kemudahan_aplikasi` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `data_konversi`
--

INSERT INTO `data_konversi` (`id`, `alternatif`, `waktu_respon`, `ketersediaan_layanan`, `kepuasan_pengguna`, `biaya_layanan`, `kemudahan_aplikasi`) VALUES
(1, 'POS', 4.00, 1.00, 4.00, 4.00, 5.00),
(2, 'SICEPAT', 5.00, 5.00, 5.00, 1.00, 5.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_matrik`
--

CREATE TABLE `data_matrik` (
  `id` int(10) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `waktu_respon` int(11) DEFAULT NULL,
  `ketersediaan_layanan` int(11) DEFAULT NULL,
  `kepuasan_pengguna` int(11) DEFAULT NULL,
  `biaya_layanan` decimal(10,2) DEFAULT NULL,
  `kemudahan_aplikasi` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `data_matrik`
--

INSERT INTO `data_matrik` (`id`, `alternatif`, `waktu_respon`, `ketersediaan_layanan`, `kepuasan_pengguna`, `biaya_layanan`, `kemudahan_aplikasi`) VALUES
(4, 'POS', 4, 1, 4, 4.00, 5),
(5, 'SICEPAT', 5, 5, 5, 1.00, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_primer`
--

CREATE TABLE `data_primer` (
  `id` int(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `waktu_respon` int(11) DEFAULT NULL,
  `ketersediaan_layanan` int(11) DEFAULT NULL,
  `kepuasan_pengguna` int(11) DEFAULT NULL,
  `biaya_layanan` decimal(10,2) DEFAULT NULL,
  `kemudahan_aplikasi` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `data_primer`
--

INSERT INTO `data_primer` (`id`, `alternatif`, `waktu_respon`, `ketersediaan_layanan`, `kepuasan_pengguna`, `biaya_layanan`, `kemudahan_aplikasi`) VALUES
(1, 'POS', 0, 0, 0, 0.00, 0),
(2, 'SICEPAT', 0, 0, 0, 0.00, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil`
--

CREATE TABLE `hasil` (
  `no` int(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `nilai_optimum` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `hasil`
--

INSERT INTO `hasil` (`no`, `alternatif`, `nilai_optimum`) VALUES
(1, '-', 0.0864),
(2, 'Haris Kurniawa', 0.0724),
(3, 'Ryan Miranda', 0.0674),
(4, 'Reza Arianda', 0.069),
(5, 'Philip', 0.0705),
(6, 'Dara Risty', 0.0596),
(7, 'Putri Afriani', 0.0565),
(8, 'Nadia', 0.0503),
(9, 'Ariansyah', 0.0674),
(10, 'Nanda', 0.0785),
(11, 'Olivia', 0.0596),
(12, 'Meghna Sharma', 0.0736),
(13, 'Fawaz Rizaka', 0.0643),
(14, 'Meihani Putri', 0.0472),
(15, 'Zura Alvira', 0.0769);

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil2`
--

CREATE TABLE `hasil2` (
  `no` int(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `nilai_akhir` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `hasil2`
--

INSERT INTO `hasil2` (`no`, `alternatif`, `nilai_akhir`) VALUES
(2, 'Haris Kurniawa', 0.838),
(3, 'Ryan Miranda', 0.7801),
(4, 'Reza Arianda', 0.7986),
(5, 'Philip', 0.816),
(6, 'Dara Risty', 0.6898),
(7, 'Putri Afriani', 0.6539),
(8, 'Nadia', 0.5822),
(9, 'Ariansyah', 0.7801),
(10, 'Nanda', 0.9086),
(11, 'Olivia', 0.6898),
(12, 'Meghna Sharma', 0.8519),
(13, 'Fawaz Rizaka', 0.7442),
(14, 'Meihani Putri', 0.5463),
(15, 'Zura Alvira', 0.89);

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_piprecia`
--

CREATE TABLE `hasil_piprecia` (
  `no` int(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `nilai_akhir` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `hasil_piprecia`
--

INSERT INTO `hasil_piprecia` (`no`, `alternatif`, `nilai_akhir`) VALUES
(1, 'Haris Kurniawa', 0.825),
(2, 'Ryan Miranda', 0.7878),
(3, 'Reza Arianda', 0.8468),
(4, 'Philip', 0.8619),
(5, 'Dara Risty', 0.7175),
(6, 'Putri Afriani', 0.6868),
(7, 'Nadia', 0.5878),
(8, 'Ariansyah', 0.7878),
(9, 'Nanda', 0.9297),
(10, 'Olivia', 0.7175),
(11, 'Meghna Sharma', 0.8897),
(12, 'Fawaz Rizaka', 0.7599),
(13, 'Meihani Putri', 0.5599),
(15, 'Zura Alvira', 0.917);

-- --------------------------------------------------------

--
-- Struktur dari tabel `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `login`
--

INSERT INTO `login` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `normalisasi`
--

CREATE TABLE `normalisasi` (
  `id` int(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `tinggi_badan` float NOT NULL,
  `berat_badan` float NOT NULL,
  `berpenampilan_menarik` float NOT NULL,
  `menguasai_panggung` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `normalisasi`
--

INSERT INTO `normalisasi` (`id`, `alternatif`, `tinggi_badan`, `berat_badan`, `berpenampilan_menarik`, `menguasai_panggung`) VALUES
(1, '-', 0.0781, 0.1064, 0.0769, 0.0794),
(2, 'Haris Kurniawa', 0.0625, 0.1064, 0.0615, 0.0317),
(3, 'Ryan Miranda', 0.0625, 0.0638, 0.0769, 0.0794),
(4, 'Reza Arianda', 0.0781, 0.0638, 0.0615, 0.0635),
(5, 'Philip', 0.0781, 0.0638, 0.0615, 0.0794),
(6, 'Dara Risty', 0.0625, 0.0426, 0.0769, 0.0635),
(7, 'Putri Afriani', 0.0625, 0.0638, 0.0462, 0.0317),
(8, 'Nadia', 0.0469, 0.0426, 0.0615, 0.0635),
(9, 'Ariansyah', 0.0625, 0.0638, 0.0769, 0.0794),
(10, 'Nanda', 0.0781, 0.0851, 0.0769, 0.0635),
(11, 'Olivia', 0.0625, 0.0426, 0.0769, 0.0635),
(12, 'Meghna Sharma', 0.0781, 0.0638, 0.0769, 0.0794),
(13, 'Fawaz Rizaka', 0.0625, 0.0638, 0.0615, 0.0794),
(14, 'Meihani Putri', 0.0469, 0.0426, 0.0462, 0.0635),
(15, 'Zura Alvira', 0.0781, 0.0851, 0.0615, 0.0794);

-- --------------------------------------------------------

--
-- Struktur dari tabel `normalisasi_piprecia`
--

CREATE TABLE `normalisasi_piprecia` (
  `id` int(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `tinggi_badan` float NOT NULL,
  `berat_badan` float NOT NULL,
  `berpenampilan_menarik` float NOT NULL,
  `menguasai_panggung` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `normalisasi_piprecia`
--

INSERT INTO `normalisasi_piprecia` (`id`, `alternatif`, `tinggi_badan`, `berat_badan`, `berpenampilan_menarik`, `menguasai_panggung`) VALUES
(1, 'Haris Kurniawa', 0.8, 1, 0.8, 0.4),
(2, 'Ryan Miranda', 0.8, 0.6, 1, 1),
(3, 'Reza Arianda', 1, 0.6, 0.8, 0.8),
(4, 'Philip', 1, 0.6, 0.8, 1),
(5, 'Dara Risty', 0.8, 0.4, 1, 0.8),
(6, 'Putri Afriani', 0.8, 0.6, 0.6, 0.4),
(7, 'Nadia', 0.6, 0.4, 0.8, 0.8),
(8, 'Ariansyah', 0.8, 0.6, 1, 1),
(9, 'Nanda', 1, 0.8, 1, 0.8),
(10, 'Olivia', 0.8, 0.4, 1, 0.8),
(11, 'Meghna Sharma', 1, 0.6, 1, 1),
(12, 'Fawaz Rizaka', 0.8, 0.6, 0.8, 1),
(13, 'Meihani Putri', 0.6, 0.4, 0.6, 0.8),
(15, 'Zura Alvira', 1, 0.8, 0.8, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `normalisasi_terbobot`
--

CREATE TABLE `normalisasi_terbobot` (
  `id` int(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `tinggi_badan` float NOT NULL,
  `berat_badan` float NOT NULL,
  `berpenampilan_menarik` float NOT NULL,
  `menguasai_panggung` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `normalisasi_terbobot`
--

INSERT INTO `normalisasi_terbobot` (`id`, `alternatif`, `tinggi_badan`, `berat_badan`, `berpenampilan_menarik`, `menguasai_panggung`) VALUES
(1, '-', 0.0312, 0.0319, 0.0154, 0.0079),
(2, 'Haris Kurniawa', 0.025, 0.0319, 0.0123, 0.0032),
(3, 'Ryan Miranda', 0.025, 0.0191, 0.0154, 0.0079),
(4, 'Reza Arianda', 0.0312, 0.0191, 0.0123, 0.0064),
(5, 'Philip', 0.0312, 0.0191, 0.0123, 0.0079),
(6, 'Dara Risty', 0.025, 0.0128, 0.0154, 0.0064),
(7, 'Putri Afriani', 0.025, 0.0191, 0.0092, 0.0032),
(8, 'Nadia', 0.0188, 0.0128, 0.0123, 0.0064),
(9, 'Ariansyah', 0.025, 0.0191, 0.0154, 0.0079),
(10, 'Nanda', 0.0312, 0.0255, 0.0154, 0.0064),
(11, 'Olivia', 0.025, 0.0128, 0.0154, 0.0064),
(12, 'Meghna Sharma', 0.0312, 0.0191, 0.0154, 0.0079),
(13, 'Fawaz Rizaka', 0.025, 0.0191, 0.0123, 0.0079),
(14, 'Meihani Putri', 0.0188, 0.0128, 0.0092, 0.0064),
(15, 'Zura Alvira', 0.0312, 0.0255, 0.0123, 0.0079);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bobot_kriteria`
--
ALTER TABLE `bobot_kriteria`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `data_konversi`
--
ALTER TABLE `data_konversi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `data_matrik`
--
ALTER TABLE `data_matrik`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `data_primer`
--
ALTER TABLE `data_primer`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `hasil`
--
ALTER TABLE `hasil`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `hasil2`
--
ALTER TABLE `hasil2`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `hasil_piprecia`
--
ALTER TABLE `hasil_piprecia`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `normalisasi`
--
ALTER TABLE `normalisasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `normalisasi_piprecia`
--
ALTER TABLE `normalisasi_piprecia`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `normalisasi_terbobot`
--
ALTER TABLE `normalisasi_terbobot`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bobot_kriteria`
--
ALTER TABLE `bobot_kriteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `data_konversi`
--
ALTER TABLE `data_konversi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `data_matrik`
--
ALTER TABLE `data_matrik`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `data_primer`
--
ALTER TABLE `data_primer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `hasil`
--
ALTER TABLE `hasil`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `hasil2`
--
ALTER TABLE `hasil2`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `hasil_piprecia`
--
ALTER TABLE `hasil_piprecia`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `normalisasi`
--
ALTER TABLE `normalisasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `normalisasi_piprecia`
--
ALTER TABLE `normalisasi_piprecia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `normalisasi_terbobot`
--
ALTER TABLE `normalisasi_terbobot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
