-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 10, 2021 at 07:48 AM
-- Server version: 5.7.34
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_persediaan_gudang`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barang`
--

CREATE TABLE `tbl_barang` (
  `id_barang` varchar(5) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `jenis` int(11) NOT NULL,
  `stok_minimum` int(11) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT '0',
  `satuan` int(11) NOT NULL,
  `foto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_barang`
--

INSERT INTO `tbl_barang` (`id_barang`, `nama_barang`, `jenis`, `stok_minimum`, `stok`, `satuan`, `foto`) VALUES
('B0001', 'Pupuk Pukalet', 1, 10, 80, 7, 'ea9088dd8adc116108720a88032a7c6d8f5d9b7f.jpg'),
('B0002', 'Pupuk Dolomite', 1, 10, 150, 7, 'fd8cffeb4e7cb7de56e1a1fc4364d5a847fbe462.jpg'),
('B0003', 'Pupuk KCL/MOP', 1, 10, 100, 7, '6572254293b0132f7dc27181eebbfc6ea01dcea7.jpg'),
('B0004', 'Gesapax 500 SC', 6, 10, 10, 5, '11848deee6a78aedbc3c884eabe09ce9d704643d.jpg'),
('B0005', 'Amonia Cair', 10, 10, 5, 5, NULL),
('B0006', 'Asam Sulfate PA 731', 10, 10, 90, 5, NULL),
('B0007', 'Vitamin Karet Plus', 2, 10, 40, 7, '6acfd4ead5a37d922a37539b0f660a44e45f290d.png'),
('B0008', 'Matador 25 EC', 5, 10, 100, 5, 'e07fd8c62c7df5219fc13bed6620c9c026407c16.jpg'),
('B0009', 'Belerang Powder', 7, 10, 70, 7, NULL),
('B0010', 'Asam Semut', 10, 10, 130, 7, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barang_keluar`
--

CREATE TABLE `tbl_barang_keluar` (
  `id_transaksi` varchar(10) NOT NULL,
  `tanggal` date NOT NULL,
  `barang` varchar(5) NOT NULL,
  `jumlah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_barang_keluar`
--

INSERT INTO `tbl_barang_keluar` (`id_transaksi`, `tanggal`, `barang`, `jumlah`) VALUES
('TK-0000001', '2021-08-03', 'B0001', 20),
('TK-0000002', '2021-08-03', 'B0007', 10),
('TK-0000003', '2021-08-04', 'B0003', 50),
('TK-0000004', '2021-08-07', 'B0004', 40),
('TK-0000005', '2021-08-07', 'B0006', 10),
('TK-0000006', '2021-08-11', 'B0005', 20),
('TK-0000007', '2021-08-13', 'B0010', 50),
('TK-0000008', '2021-08-13', 'B0002', 30),
('TK-0000009', '2021-08-15', 'B0009', 10),
('TK-0000010', '2021-08-15', 'B0005', 25),
('TK-0000011', '2021-08-19', 'B0009', 20),
('TK-0000012', '2021-08-27', 'B0005', 50),
('TK-0000013', '2021-08-27', 'B0002', 20),
('TK-0000014', '2021-08-30', 'B0010', 20);

--
-- Triggers `tbl_barang_keluar`
--
DELIMITER $$
CREATE TRIGGER `hapus_stok_keluar` BEFORE DELETE ON `tbl_barang_keluar` FOR EACH ROW BEGIN
UPDATE tbl_barang SET stok=stok+OLD.jumlah
WHERE id_barang=OLD.barang;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `stok_keluar` AFTER INSERT ON `tbl_barang_keluar` FOR EACH ROW BEGIN
UPDATE tbl_barang SET stok=stok-NEW.jumlah
WHERE id_barang=NEW.barang;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barang_masuk`
--

CREATE TABLE `tbl_barang_masuk` (
  `id_transaksi` varchar(10) NOT NULL,
  `tanggal` date NOT NULL,
  `barang` varchar(5) NOT NULL,
  `jumlah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_barang_masuk`
--

INSERT INTO `tbl_barang_masuk` (`id_transaksi`, `tanggal`, `barang`, `jumlah`) VALUES
('TM-0000001', '2021-08-01', 'B0001', 100),
('TM-0000002', '2021-08-01', 'B0002', 200),
('TM-0000003', '2021-08-01', 'B0003', 150),
('TM-0000004', '2021-08-01', 'B0007', 50),
('TM-0000005', '2021-08-05', 'B0004', 50),
('TM-0000006', '2021-08-05', 'B0005', 50),
('TM-0000007', '2021-08-05', 'B0006', 100),
('TM-0000008', '2021-08-05', 'B0008', 100),
('TM-0000009', '2021-08-05', 'B0009', 100),
('TM-0000010', '2021-08-05', 'B0010', 200),
('TM-0000011', '2021-08-25', 'B0005', 50);

--
-- Triggers `tbl_barang_masuk`
--
DELIMITER $$
CREATE TRIGGER `hapus_stok_masuk` BEFORE DELETE ON `tbl_barang_masuk` FOR EACH ROW BEGIN
UPDATE tbl_barang SET stok=stok-OLD.jumlah
WHERE id_barang=OLD.barang;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `stok_masuk` AFTER INSERT ON `tbl_barang_masuk` FOR EACH ROW BEGIN
UPDATE tbl_barang SET stok=stok+NEW.jumlah
WHERE id_barang=NEW.barang;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jenis`
--

CREATE TABLE `tbl_jenis` (
  `id_jenis` int(11) NOT NULL,
  `nama_jenis` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_jenis`
--

INSERT INTO `tbl_jenis` (`id_jenis`, `nama_jenis`) VALUES
(1, 'Pupuk Kimia Alam'),
(2, 'Pupuk Hijau'),
(3, 'Pelumas Mesin Pabrik'),
(4, 'Pelumas Kendaraan dan Mesin'),
(5, 'Insektisida'),
(6, 'Herbisida'),
(7, 'Fungisida'),
(8, 'Bahan Stimulasi'),
(9, 'Bahan Pengepakan'),
(10, 'Bahan Kimia Pengolahan'),
(11, 'Bahan Cat dan Kapur'),
(12, 'Bahan Bakar'),
(13, 'Alat Perlengkapan Pertanian'),
(14, 'Persediaan Bahan Lain');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_satuan`
--

CREATE TABLE `tbl_satuan` (
  `id_satuan` int(11) NOT NULL,
  `nama_satuan` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_satuan`
--

INSERT INTO `tbl_satuan` (`id_satuan`, `nama_satuan`) VALUES
(1, 'Tabung'),
(2, 'Set'),
(3, 'Roll'),
(4, 'Meter'),
(5, 'Liter'),
(6, 'Lembar'),
(7, 'Kilogram'),
(8, 'Gram'),
(9, 'Buah'),
(10, 'Botol');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hak_akses` enum('Administrator','Admin Gudang','Kepala Gudang') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `nama_user`, `username`, `password`, `hak_akses`) VALUES
(1, 'Indra Setyawantoro', 'administrator', '$2y$12$Yi/I5f1jPoQNQnh6lWoVfuz.RtZ3OHcKN6PU.I62P0fYK1tJ7xMRi', 'Administrator'),
(2, 'Danang Kesuma', 'admin gudang', '$2y$12$BeRYh13zfPXej97VgcfeNucYJGTElha5sRyIUQm1278D2u2Aqf6DS', 'Admin Gudang'),
(3, 'Arshaka Keenandra', 'kepala gudang', '$2y$12$odXcPs.RLJJH6Ghv3s42c.5zg5qAOz/S3Adr0lXGNcVSJ6f1hHS6G', 'Kepala Gudang');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_barang`
--
ALTER TABLE `tbl_barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `tbl_barang_keluar`
--
ALTER TABLE `tbl_barang_keluar`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indexes for table `tbl_barang_masuk`
--
ALTER TABLE `tbl_barang_masuk`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indexes for table `tbl_jenis`
--
ALTER TABLE `tbl_jenis`
  ADD PRIMARY KEY (`id_jenis`);

--
-- Indexes for table `tbl_satuan`
--
ALTER TABLE `tbl_satuan`
  ADD PRIMARY KEY (`id_satuan`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_jenis`
--
ALTER TABLE `tbl_jenis`
  MODIFY `id_jenis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_satuan`
--
ALTER TABLE `tbl_satuan`
  MODIFY `id_satuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
