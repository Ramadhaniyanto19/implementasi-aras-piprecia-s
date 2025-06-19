-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2025 at 11:01 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
-- Table structure for table `bobot_kriteria`
--

CREATE TABLE `bobot_kriteria` (
  `id` int(11) NOT NULL,
  `kriteria` varchar(50) NOT NULL,
  `bobot_piprecia` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bobot_kriteria`
--

INSERT INTO `bobot_kriteria` (`id`, `kriteria`, `bobot_piprecia`) VALUES
(1, 'tinggi_badan', 0.35),
(2, 'berat_badan', 0.12),
(3, 'berpenampilan_menarik', 0.35),
(4, 'menguasai_panggung', 0.18);

-- --------------------------------------------------------

--
-- Table structure for table `data_konversi`
--

CREATE TABLE `data_konversi` (
  `id` int(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `tinggi_badan` int(10) NOT NULL,
  `berat_badan` int(5) NOT NULL,
  `berpenampilan_menarik` varchar(15) NOT NULL,
  `menguasai_panggung` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `data_konversi`
--

INSERT INTO `data_konversi` (`id`, `alternatif`, `tinggi_badan`, `berat_badan`, `berpenampilan_menarik`, `menguasai_panggung`) VALUES
(1, 'Haris Kurniawa', 4, 5, '4', '2'),
(2, 'Ryan Miranda', 4, 3, '5', '5'),
(3, 'Reza Arianda', 5, 3, '4', '4'),
(4, 'Philip', 5, 3, '4', '5'),
(5, 'Dara Risty', 4, 2, '5', '4'),
(6, 'Putri Afriani', 4, 3, '3', '2'),
(7, 'Nadia', 3, 2, '4', '4'),
(8, 'Ariansyah', 4, 3, '5', '5'),
(9, 'Nanda', 5, 4, '5', '4'),
(10, 'Olivia', 4, 2, '5', '4'),
(11, 'Meghna Sharma', 5, 3, '5', '5'),
(12, 'Fawaz Rizaka', 4, 3, '4', '5'),
(13, 'Meihani Putri', 3, 2, '3', '4'),
(15, 'Zura Alvira', 5, 4, '4', '5');

-- --------------------------------------------------------

--
-- Table structure for table `data_matrik`
--

CREATE TABLE `data_matrik` (
  `id` int(10) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `tinggi_badan` int(10) NOT NULL,
  `berat_badan` int(10) NOT NULL,
  `berpenampilan_menarik` int(15) NOT NULL,
  `menguasai_panggung` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `data_matrik`
--

INSERT INTO `data_matrik` (`id`, `alternatif`, `tinggi_badan`, `berat_badan`, `berpenampilan_menarik`, `menguasai_panggung`) VALUES
(1, '-', 5, 5, 5, 5),
(2, 'Haris Kurniawa', 4, 5, 4, 2),
(3, 'Ryan Miranda', 4, 3, 5, 5),
(4, 'Reza Arianda', 5, 3, 4, 4),
(5, 'Philip', 5, 3, 4, 5),
(6, 'Dara Risty', 4, 2, 5, 4),
(7, 'Putri Afriani', 4, 3, 3, 2),
(8, 'Nadia', 3, 2, 4, 4),
(9, 'Ariansyah', 4, 3, 5, 5),
(10, 'Nanda', 5, 4, 5, 4),
(11, 'Olivia', 4, 2, 5, 4),
(12, 'Meghna Sharma', 5, 3, 5, 5),
(13, 'Fawaz Rizaka', 4, 3, 4, 5),
(14, 'Meihani Putri', 3, 2, 3, 4),
(15, 'Zura Alvira', 5, 4, 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `data_primer`
--

CREATE TABLE `data_primer` (
  `id` int(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `tinggi_badan` int(10) NOT NULL,
  `berat_badan` int(5) NOT NULL,
  `berpenampilan_menarik` varchar(15) NOT NULL,
  `menguasai_panggung` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `data_primer`
--

INSERT INTO `data_primer` (`id`, `alternatif`, `tinggi_badan`, `berat_badan`, `berpenampilan_menarik`, `menguasai_panggung`) VALUES
(1, 'Haris Kurniawa', 175, 80, 'Menarik', 'Kurang Baik'),
(2, 'Ryan Miranda', 178, 60, 'Sangat Menarik', 'Sangat Baik'),
(3, 'Reza Arianda', 182, 60, 'Menarik', 'Baik'),
(4, 'Philip', 180, 60, 'Menarik', 'Sangat Baik'),
(5, 'Dara Risty', 170, 50, 'Sangat Menarik', 'Baik'),
(6, 'Putri Afriani', 170, 55, 'Cukup', 'Kurang Baik'),
(7, 'Nadia', 168, 49, 'Menarik', 'Baik'),
(8, 'Ariansyah', 173, 58, 'Sangat Menarik', 'Sangat Baik'),
(9, 'Nanda', 180, 71, 'Sangat Menarik', 'Baik'),
(10, 'Olivia', 174, 50, 'Sangat Menarik', 'Baik'),
(11, 'Meghna Sharma', 183, 60, 'Sangat Menarik', 'Sangat Baik'),
(12, 'Fawaz Rizaka', 179, 58, 'Menarik', 'Sangat Baik'),
(13, 'Meihani Putri', 165, 50, 'Cukup', 'Baik'),
(15, 'Zura Alvira', 180, 65, 'Menarik', 'Sangat Baik');

-- --------------------------------------------------------

--
-- Table structure for table `hasil`
--

CREATE TABLE `hasil` (
  `no` int(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `nilai_optimum` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hasil`
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
-- Table structure for table `hasil2`
--

CREATE TABLE `hasil2` (
  `no` int(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `nilai_akhir` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hasil2`
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
-- Table structure for table `hasil_piprecia`
--

CREATE TABLE `hasil_piprecia` (
  `no` int(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `nilai_akhir` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hasil_piprecia`
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
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `normalisasi`
--

CREATE TABLE `normalisasi` (
  `id` int(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `tinggi_badan` float NOT NULL,
  `berat_badan` float NOT NULL,
  `berpenampilan_menarik` float NOT NULL,
  `menguasai_panggung` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `normalisasi`
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
-- Table structure for table `normalisasi_piprecia`
--

CREATE TABLE `normalisasi_piprecia` (
  `id` int(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `tinggi_badan` float NOT NULL,
  `berat_badan` float NOT NULL,
  `berpenampilan_menarik` float NOT NULL,
  `menguasai_panggung` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `normalisasi_piprecia`
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
-- Table structure for table `normalisasi_terbobot`
--

CREATE TABLE `normalisasi_terbobot` (
  `id` int(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `tinggi_badan` float NOT NULL,
  `berat_badan` float NOT NULL,
  `berpenampilan_menarik` float NOT NULL,
  `menguasai_panggung` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `normalisasi_terbobot`
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
-- Indexes for table `bobot_kriteria`
--
ALTER TABLE `bobot_kriteria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_konversi`
--
ALTER TABLE `data_konversi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_matrik`
--
ALTER TABLE `data_matrik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_primer`
--
ALTER TABLE `data_primer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hasil`
--
ALTER TABLE `hasil`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `hasil2`
--
ALTER TABLE `hasil2`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `hasil_piprecia`
--
ALTER TABLE `hasil_piprecia`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `normalisasi`
--
ALTER TABLE `normalisasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `normalisasi_piprecia`
--
ALTER TABLE `normalisasi_piprecia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `normalisasi_terbobot`
--
ALTER TABLE `normalisasi_terbobot`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bobot_kriteria`
--
ALTER TABLE `bobot_kriteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `data_konversi`
--
ALTER TABLE `data_konversi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `data_matrik`
--
ALTER TABLE `data_matrik`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `data_primer`
--
ALTER TABLE `data_primer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `hasil`
--
ALTER TABLE `hasil`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `hasil2`
--
ALTER TABLE `hasil2`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `hasil_piprecia`
--
ALTER TABLE `hasil_piprecia`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `normalisasi`
--
ALTER TABLE `normalisasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `normalisasi_piprecia`
--
ALTER TABLE `normalisasi_piprecia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `normalisasi_terbobot`
--
ALTER TABLE `normalisasi_terbobot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
