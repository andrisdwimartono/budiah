-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2018 at 09:47 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dashboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `CusCod` varchar(15) NOT NULL,
  `Nama` varchar(50) NOT NULL,
  `Stat` date DEFAULT NULL,
  `RM` varchar(50) DEFAULT NULL,
  `Branch` varchar(75) DEFAULT NULL,
  `Region` varchar(75) DEFAULT NULL,
  `GroupNasabah` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `CusCod`, `Nama`, `Stat`, `RM`, `Branch`, `Region`, `GroupNasabah`) VALUES
(1, 'NCC', 'Nama Customer', '2017-12-23', 'RM', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `file_transaksi`
--

CREATE TABLE `file_transaksi` (
  `id` int(11) NOT NULL,
  `nama_file` varchar(250) NOT NULL,
  `deskripsi` text,
  `waktu_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `jenis_transaksi` varchar(50) NOT NULL,
  `last_update` timestamp NULL DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `file_transaksi`
--

INSERT INTO `file_transaksi` (`id`, `nama_file`, `deskripsi`, `waktu_upload`, `jenis_transaksi`, `last_update`, `status`) VALUES
(1, 'gmra_.xlsx', 'deskripsi', '2017-12-23 05:47:10', 'gmra', NULL, 'inserted'),
(2, 'gmra_2.xlsx', 'deskripsi 2', '2017-12-23 05:48:25', 'gmra', NULL, 'inserted'),
(3, 'gmra_.xlsx', 'deskripsi', '2017-12-23 05:49:25', 'gmra', NULL, 'inserted'),
(4, 'gmra_.xlsx', 'deskripsi', '2017-12-23 06:09:35', 'bond', NULL, 'inserted'),
(5, 'gmra_.xlsx', 'deskripsi', '2017-12-23 05:49:25', 'gmra', NULL, 'inserted'),
(6, 'gmra_.xlsx', 'deskripsi', '2017-12-23 05:49:25', 'gmra', NULL, 'inserted'),
(7, 'gmra_.xlsx', 'deskripsi', '2017-12-23 05:49:25', 'gmra', NULL, 'inserted'),
(8, 'gmra_.xlsx', 'deskripsi', '2017-12-23 05:49:25', 'gmra', NULL, 'inserted'),
(9, 'gmra_.xlsx', 'deskripsi', '2017-12-23 05:49:25', 'gmra', NULL, 'inserted'),
(10, 'gmra_.xlsx', 'deskripsi', '2017-12-23 05:49:25', 'gmra', NULL, 'inserted'),
(11, 'gmra_.xlsx', 'deskripsi', '2017-12-23 05:49:25', 'gmra', NULL, 'inserted'),
(12, 'gmra_.xlsx', 'deskripsi', '2017-12-23 05:49:25', 'gmra', NULL, 'inserted');

-- --------------------------------------------------------

--
-- Table structure for table `kanwil`
--

CREATE TABLE `kanwil` (
  `id` int(11) NOT NULL,
  `Nama` varchar(35) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kanwil`
--

INSERT INTO `kanwil` (`id`, `Nama`, `created_at`) VALUES
(1, 'DKI 1', '2018-01-01 14:27:08'),
(2, 'DKI 2', '2018-01-01 14:27:08'),
(3, 'DKI 3', '2018-01-01 14:27:23'),
(4, 'Jawa Barat', '2018-01-01 14:27:23'),
(5, 'Jawa Tengah', '2018-01-01 14:27:38'),
(6, 'Jawa Timur', '2018-01-01 14:27:38');

-- --------------------------------------------------------

--
-- Table structure for table `target`
--

CREATE TABLE `target` (
  `id` int(11) NOT NULL,
  `tahun` int(4) NOT NULL,
  `volumefx` decimal(30,2) DEFAULT NULL,
  `transaksifx` decimal(30,2) DEFAULT NULL,
  `nasabahfx` decimal(30,2) DEFAULT NULL,
  `volumebond` decimal(30,2) DEFAULT NULL,
  `nasabahbond` decimal(30,2) DEFAULT NULL,
  `transaksigmra` decimal(30,2) DEFAULT NULL,
  `nasabahgmra` decimal(30,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `target`
--

INSERT INTO `target` (`id`, `tahun`, `volumefx`, `transaksifx`, `nasabahfx`, `volumebond`, `nasabahbond`, `transaksigmra`, `nasabahgmra`) VALUES
(1, 2017, '1000000000.00', '10000000.00', '999999998.00', '1000000000.00', '1000000000.00', '1000000000.00', '1000000000.00');

-- --------------------------------------------------------

--
-- Table structure for table `target_kanwil`
--

CREATE TABLE `target_kanwil` (
  `id` int(11) NOT NULL,
  `fk_kanwil_id` int(11) NOT NULL,
  `tahun` int(4) NOT NULL,
  `target_volume` double DEFAULT NULL,
  `target_transaksi` double DEFAULT NULL,
  `target_jml_nasabah` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `target_kanwil`
--

INSERT INTO `target_kanwil` (`id`, `fk_kanwil_id`, `tahun`, `target_volume`, `target_transaksi`, `target_jml_nasabah`) VALUES
(1, 1, 2017, 20034500, 10, NULL),
(2, 2, 2017, 2091238912, 20, NULL),
(3, 3, 2017, 2091238912, 11, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_bond`
--

CREATE TABLE `transaksi_bond` (
  `id` int(11) NOT NULL,
  `id_file_transaksi` int(11) DEFAULT NULL,
  `trade_date` date NOT NULL,
  `settlement_date` date NOT NULL,
  `customer` varchar(250) DEFAULT NULL,
  `buy_sell` varchar(50) NOT NULL,
  `security_id` varchar(50) DEFAULT NULL,
  `kupon` double DEFAULT NULL,
  `quantity` decimal(30,2) NOT NULL,
  `price` double DEFAULT NULL,
  `principal` decimal(30,2) DEFAULT NULL,
  `yield` double DEFAULT NULL,
  `accrued_interest` decimal(30,2) DEFAULT NULL,
  `total_cash` decimal(30,2) DEFAULT NULL,
  `dealer` varchar(50) DEFAULT NULL,
  `kurs_penutupan_trade_date` decimal(30,2) DEFAULT NULL,
  `volume` decimal(30,2) DEFAULT NULL,
  `pasar` varchar(50) DEFAULT NULL,
  `bank_nonbank` varchar(50) DEFAULT NULL,
  `marketprice_bid` double DEFAULT NULL,
  `marketprice_ask` double DEFAULT NULL,
  `LastUpdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaksi_bond`
--

INSERT INTO `transaksi_bond` (`id`, `id_file_transaksi`, `trade_date`, `settlement_date`, `customer`, `buy_sell`, `security_id`, `kupon`, `quantity`, `price`, `principal`, `yield`, `accrued_interest`, `total_cash`, `dealer`, `kurs_penutupan_trade_date`, `volume`, `pasar`, `bank_nonbank`, `marketprice_bid`, `marketprice_ask`, `LastUpdate`) VALUES
(175, 1, '2017-12-14', '2017-12-15', 'ASD', 'SELL', 'ASD', 123123, '123123123.00', 123123123120, '12334234234.00', 34253425, '2130.00', '23412342314.00', 'DEALRE', '123123324.00', '245221.00', 'PASAR', 'BANK', NULL, NULL, '2017-12-14 22:53:25'),
(176, 1, '2017-12-14', '2017-12-14', 'ADS', 'BUY', '123123', 123123123, '213213.00', 12341234, '21342314.00', 2134232, '111.00', NULL, '', '21.00', NULL, '', 'NON BANK', NULL, NULL, '2017-12-14 23:20:11');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_fx`
--

CREATE TABLE `transaksi_fx` (
  `id` int(11) NOT NULL,
  `id_file_transaksi` int(11) NOT NULL,
  `idTran` varchar(15) NOT NULL,
  `Status` varchar(256) DEFAULT NULL,
  `TanggalTransaksi` date DEFAULT NULL,
  `Direction` varchar(10) DEFAULT NULL,
  `ValueDate` date DEFAULT NULL,
  `DealDate` date DEFAULT NULL,
  `DealerName` varchar(256) DEFAULT NULL,
  `Counterparty` varchar(256) DEFAULT NULL,
  `Kanwil` varchar(256) DEFAULT NULL,
  `Cabang` varchar(256) DEFAULT NULL,
  `DealtCurrency` varchar(50) DEFAULT NULL,
  `DealtAmount` double DEFAULT NULL,
  `CounterCurrency` varchar(50) DEFAULT NULL,
  `CounterAmount` double DEFAULT NULL,
  `Rate` float DEFAULT NULL,
  `EkuivalentUSD` double DEFAULT NULL,
  `Dealer` varchar(128) DEFAULT NULL,
  `DealerOTC` varchar(128) DEFAULT NULL,
  `NPWP` varchar(64) DEFAULT NULL,
  `TimeExpired` datetime DEFAULT NULL,
  `UntungRugi` double NOT NULL,
  `CusCod` varchar(15) NOT NULL,
  `LastUpdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaksi_fx`
--

INSERT INTO `transaksi_fx` (`id`, `id_file_transaksi`, `idTran`, `Status`, `TanggalTransaksi`, `Direction`, `ValueDate`, `DealDate`, `DealerName`, `Counterparty`, `Kanwil`, `Cabang`, `DealtCurrency`, `DealtAmount`, `CounterCurrency`, `CounterAmount`, `Rate`, `EkuivalentUSD`, `Dealer`, `DealerOTC`, `NPWP`, `TimeExpired`, `UntungRugi`, `CusCod`, `LastUpdate`) VALUES
(1, 0, 'O091239', 'SUKSES KREDIT STO', '2017-12-11', 'BUY', '2017-12-23', '1970-01-01', '', '', 'DKI 1', '', '', NULL, '', NULL, NULL, 356436, '', '', '', '2017-12-23 00:00:00', 123123123, 'asd12213', '2018-01-06 16:35:37'),
(2, 0, 'O091239', 'SUKSES KREDIT STO', '2017-12-11', 'BUY', '2018-01-17', '2018-01-18', '', '', 'DKI 1', '', 'USD', 871236812, 'USD', 34534252345, 32453200, 356437, 'd', '', '', '1970-01-01 00:00:00', 98134223874, 'COD', '2018-01-06 16:44:26'),
(3, 0, 'O091239', 'SUKSES KREDIT STO', '2017-12-11', 'BUY', '2017-12-23', '1970-01-01', '', '', 'DKI 2', '', '', NULL, '', NULL, NULL, 356436, '', '', '', '2017-12-23 00:00:00', 123123123, 'asd12213', '2018-01-06 16:35:39');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_gmra`
--

CREATE TABLE `transaksi_gmra` (
  `id` int(11) NOT NULL,
  `id_file_transaksi` int(11) DEFAULT NULL,
  `trade_date` date NOT NULL,
  `settlement_date_1st_leg` date DEFAULT NULL,
  `settlement_date_2nd_leg` date DEFAULT NULL,
  `customer` varchar(250) DEFAULT NULL,
  `r_rr` varchar(5) NOT NULL,
  `tenor` int(11) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `quantity` decimal(30,2) NOT NULL,
  `price_1st_leg` double DEFAULT NULL,
  `amount_1st_leg` decimal(30,2) DEFAULT NULL,
  `amount_2nd_leg` decimal(30,2) DEFAULT NULL,
  `dealer` varchar(250) DEFAULT NULL,
  `marketprice_bid` double DEFAULT NULL,
  `marketprice_ask` double DEFAULT NULL,
  `LastUpdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaksi_gmra`
--

INSERT INTO `transaksi_gmra` (`id`, `id_file_transaksi`, `trade_date`, `settlement_date_1st_leg`, `settlement_date_2nd_leg`, `customer`, `r_rr`, `tenor`, `rate`, `quantity`, `price_1st_leg`, `amount_1st_leg`, `amount_2nd_leg`, `dealer`, `marketprice_bid`, `marketprice_ask`, `LastUpdate`) VALUES
(116, 1, '2017-12-14', '2017-12-16', '1970-01-01', 'CUS', 'R', 122, NULL, '1.00', NULL, NULL, NULL, '', NULL, NULL, '2017-12-14 23:47:05'),
(117, 1, '2017-12-14', '1970-01-01', '1970-01-01', '', 'R', NULL, NULL, '123.00', NULL, NULL, NULL, '', NULL, NULL, '2017-12-14 23:48:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file_transaksi`
--
ALTER TABLE `file_transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kanwil`
--
ALTER TABLE `kanwil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `target`
--
ALTER TABLE `target`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `target_kanwil`
--
ALTER TABLE `target_kanwil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `target_per_kanwil` (`fk_kanwil_id`);

--
-- Indexes for table `transaksi_bond`
--
ALTER TABLE `transaksi_bond`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `transaksi_fx`
--
ALTER TABLE `transaksi_fx`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi_gmra`
--
ALTER TABLE `transaksi_gmra`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `file_transaksi`
--
ALTER TABLE `file_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `kanwil`
--
ALTER TABLE `kanwil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `target`
--
ALTER TABLE `target`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `target_kanwil`
--
ALTER TABLE `target_kanwil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `transaksi_bond`
--
ALTER TABLE `transaksi_bond`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;
--
-- AUTO_INCREMENT for table `transaksi_fx`
--
ALTER TABLE `transaksi_fx`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `transaksi_gmra`
--
ALTER TABLE `transaksi_gmra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `target_kanwil`
--
ALTER TABLE `target_kanwil`
  ADD CONSTRAINT `target_per_kanwil` FOREIGN KEY (`fk_kanwil_id`) REFERENCES `kanwil` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
