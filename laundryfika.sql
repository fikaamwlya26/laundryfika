-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2024 at 07:01 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laundryfika`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kode_invoice` varchar(50) DEFAULT NULL,
  `status` enum('Baru','Proses','Selesai','Diambil') DEFAULT NULL,
  `pelanggan` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `batas_waktu` datetime DEFAULT NULL,
  `tanggal_dibayar` datetime DEFAULT NULL,
  `dibayar` enum('Sudah','Belum') DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id`, `nama`, `kode_invoice`, `status`, `pelanggan`, `tanggal`, `batas_waktu`, `tanggal_dibayar`, `dibayar`, `total`) VALUES
(0, '', '2024-11-19T10:00', 'Selesai', 'Sabrina', '2024-11-19 10:00:00', '2024-11-23 10:00:00', '2024-11-19 13:00:00', 'Sudah', 580.00);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
