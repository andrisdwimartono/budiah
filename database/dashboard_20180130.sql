-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2018 at 11:15 PM
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
-- Table structure for table `closing_rate`
--

CREATE TABLE `closing_rate` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `ccy` varchar(20) NOT NULL,
  `closingrate` decimal(30,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `closing_rate`
--

INSERT INTO `closing_rate` (`id`, `tanggal`, `ccy`, `closingrate`) VALUES
(1, '2017-12-13', 'a', '12.00');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `id_file_transaksi` int(11) DEFAULT NULL,
  `CusCod` varchar(15) NOT NULL,
  `Nama` varchar(50) NOT NULL,
  `Stat` date DEFAULT NULL,
  `RM` varchar(50) DEFAULT NULL,
  `BranchCode` varchar(15) DEFAULT NULL,
  `Branch` varchar(75) DEFAULT NULL,
  `Region` varchar(75) DEFAULT NULL,
  `Segmen` varchar(15) DEFAULT NULL,
  `GroupNasabah` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `id_file_transaksi`, `CusCod`, `Nama`, `Stat`, `RM`, `BranchCode`, `Branch`, `Region`, `Segmen`, `GroupNasabah`) VALUES
(1, NULL, 'IKPP', 'INDAH KIAT PULP AND PAPER PT', '2017-12-16', 'RM', NULL, 'DKI 1', 'DKI 1', NULL, 'GROUP B'),
(2, NULL, 'IKPP', 'ikpP', '2017-12-05', 'RM', NULL, '', '', NULL, 'GROUP A'),
(3, NULL, 'RIAA', 'RIAU ANDALAN KERTAS', '2018-01-07', 'RM', NULL, 'PANGKALAN KERINCI', 'KANWIL PEKANBARU', NULL, 'GROUP A'),
(4, NULL, 'AFIM', 'AFIMA', '2018-01-22', 'RM', NULL, '', '', NULL, 'GROUP B');

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

-- --------------------------------------------------------

--
-- Table structure for table `group_nasabah`
--

CREATE TABLE `group_nasabah` (
  `id` int(11) NOT NULL,
  `CusGroup` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group_nasabah`
--

INSERT INTO `group_nasabah` (`id`, `CusGroup`) VALUES
(2, 'GROUP A'),
(3, 'GROUP B'),
(5, 'GROUP C');

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
-- Stand-in structure for view `summary_fx`
-- (See below for the actual view)
--
CREATE TABLE `summary_fx` (
`customer` varchar(50)
,`quantity` decimal(52,2)
,`count` bigint(21)
,`year` int(4)
);

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
(1, 2017, '123.00', '123123.00', '122.00', '123.00', '1112.00', '1112.00', '213123.00'),
(2, 2018, '1233.00', '11.00', '12.00', '1233.00', '11.00', '1233.00', '11.00');

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
(1, 6, 2017, 123123, 123, 12),
(2, 1, 2017, 123123, 123, 11),
(3, 3, 2017, 123, 123, 123);

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
(1, NULL, '2018-12-14', '2018-12-23', 'CUS', 'BUY', 'ASD', 123123, '123123.00', 123123, '123.00', 118, '123.00', NULL, '', NULL, NULL, '', 'BANK', NULL, NULL, '2018-01-21 20:59:27'),
(3, NULL, '2018-12-14', '2018-12-23', 'CUS', 'BUY', 'ASD', 123123, '123123.00', 123123, '123.00', 118, '123.00', NULL, '', NULL, NULL, '', 'BANK', NULL, NULL, '2018-01-21 20:59:27'),
(4, NULL, '2018-12-14', '2018-12-23', 'CUS', 'BUY', 'ASD', 123123, '123123.00', 123123, '123.00', 118, '123.00', NULL, '', NULL, NULL, '', 'BANK', NULL, NULL, '2018-01-21 20:59:27'),
(5, NULL, '2018-12-14', '2018-12-23', 'CUS', 'BUY', 'ASD', 123123, '123123.00', 123123, '123.00', 118, '123.00', NULL, '', NULL, NULL, '', 'BANK', NULL, NULL, '2018-01-21 20:59:27'),
(6, NULL, '2018-12-14', '2018-12-23', 'CUS', 'BUY', 'ASD', 123123, '123123.00', 123123, '123.00', 118, '123.00', NULL, '', NULL, NULL, '', 'BANK', NULL, NULL, '2018-01-21 20:59:27'),
(7, NULL, '2018-12-14', '2018-12-23', 'CUS', 'BUY', 'ASD', 123123, '123123.00', 123123, '123.00', 118, '123.00', NULL, '', NULL, NULL, '', 'BANK', NULL, NULL, '2018-01-21 20:59:27'),
(8, NULL, '2018-12-14', '2018-12-23', 'CUS', 'BUY', 'ASD', 123123, '123123.00', 123123, '123.00', 118, '123.00', NULL, '', NULL, NULL, '', 'BANK', NULL, NULL, '2018-01-21 20:59:27'),
(9, NULL, '2018-12-14', '2018-12-23', 'CUS', 'BUY', 'ASD', 123123, '123123.00', 123123, '123.00', 118, '123.00', NULL, '', NULL, NULL, '', 'BANK', NULL, NULL, '2018-01-21 20:59:27'),
(10, NULL, '2018-12-14', '2018-12-23', 'CUS', 'BUY', 'ASD', 123123, '123123.00', 123123, '123.00', 118, '123.00', NULL, '', NULL, NULL, '', 'BANK', NULL, NULL, '2018-01-21 20:59:27'),
(11, NULL, '2018-12-14', '2018-12-23', 'CUS', 'BUY', 'ASD', 123123, '123123.00', 123123, '123.00', 118, '123.00', NULL, '', NULL, NULL, '', 'BANK', NULL, NULL, '2018-01-21 20:59:27');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_fx`
--

CREATE TABLE `transaksi_fx` (
  `id` int(11) NOT NULL,
  `id_file_transaksi` int(11) DEFAULT NULL,
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
  `DealtAmount` double(30,2) DEFAULT NULL,
  `CounterCurrency` varchar(50) DEFAULT NULL,
  `CounterAmount` double DEFAULT NULL,
  `Rate` float DEFAULT NULL,
  `EkuivalentUSD` decimal(30,2) DEFAULT NULL,
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
(1, NULL, 'O091239', 'INPUT', NULL, 'BUY', '2017-12-13', '2017-12-12', 'asd', 'asd', 'DKI 1', 'ad', '', NULL, '', NULL, 324, '2131.00', '223', '', '', '1970-01-01 00:00:00', 123123, 'RIAA', '2018-01-25 23:16:19'),
(2, NULL, 'O091239x', 'INPUT', NULL, 'BUY', '2018-01-17', '2017-01-10', 'asdx', 'asdx', 'DKI 1', '', '', NULL, '', NULL, NULL, '1111.00', '', '', '', '1970-01-01 00:00:00', 123123, 'IKPP', '2018-01-25 23:18:26'),
(3, NULL, 'TR01', 'INPUT', NULL, 'SELL', '2017-12-13', '2017-12-11', '', '', 'Jawa Timur', '', '', NULL, '', NULL, NULL, '1177.00', '', '', '', '1970-01-01 00:00:00', 122, 'AFIM', '2018-01-25 22:43:35');

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
(1, NULL, '2017-12-05', '2017-12-12', '2017-12-18', 'CUS', 'R', NULL, NULL, '12.00', NULL, NULL, NULL, 'DEa', NULL, NULL, '2018-01-25 00:51:06'),
(2, NULL, '2017-12-01', '2017-12-20', '2017-12-18', '', 'R', NULL, NULL, '123.00', NULL, NULL, NULL, '', NULL, NULL, '2018-01-25 00:51:59');

-- --------------------------------------------------------

--
-- Structure for view `summary_fx`
--
DROP TABLE IF EXISTS `summary_fx`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `summary_fx`  AS  select `c`.`Nama` AS `customer`,sum(`t`.`EkuivalentUSD`) AS `quantity`,count(`t`.`EkuivalentUSD`) AS `count`,year(`t`.`DealDate`) AS `year` from (`transaksi_fx` `t` join `customer` `c` on((`t`.`CusCod` = `c`.`CusCod`))) where (`t`.`Status` in ('INPUT','SUKSES KREDIT STO','SUKSES SISTEM LAIN','REJECT EC KREDIT')) group by `c`.`Nama`,year(`t`.`DealDate`) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `closing_rate`
--
ALTER TABLE `closing_rate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_file_transaksi` (`id_file_transaksi`);

--
-- Indexes for table `file_transaksi`
--
ALTER TABLE `file_transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_nasabah`
--
ALTER TABLE `group_nasabah`
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
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `id_file_transaksi` (`id_file_transaksi`);

--
-- Indexes for table `transaksi_fx`
--
ALTER TABLE `transaksi_fx`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_file_transaksi` (`id_file_transaksi`);

--
-- Indexes for table `transaksi_gmra`
--
ALTER TABLE `transaksi_gmra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_file_transaksi` (`id_file_transaksi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `closing_rate`
--
ALTER TABLE `closing_rate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `file_transaksi`
--
ALTER TABLE `file_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `group_nasabah`
--
ALTER TABLE `group_nasabah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `kanwil`
--
ALTER TABLE `kanwil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `target`
--
ALTER TABLE `target`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `target_kanwil`
--
ALTER TABLE `target_kanwil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `transaksi_bond`
--
ALTER TABLE `transaksi_bond`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `transaksi_fx`
--
ALTER TABLE `transaksi_fx`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `transaksi_gmra`
--
ALTER TABLE `transaksi_gmra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`id_file_transaksi`) REFERENCES `file_transaksi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `target_kanwil`
--
ALTER TABLE `target_kanwil`
  ADD CONSTRAINT `target_per_kanwil` FOREIGN KEY (`fk_kanwil_id`) REFERENCES `kanwil` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `transaksi_bond`
--
ALTER TABLE `transaksi_bond`
  ADD CONSTRAINT `transaksi_bond_ibfk_1` FOREIGN KEY (`id_file_transaksi`) REFERENCES `file_transaksi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi_fx`
--
ALTER TABLE `transaksi_fx`
  ADD CONSTRAINT `transaksi_fx_ibfk_1` FOREIGN KEY (`id_file_transaksi`) REFERENCES `file_transaksi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi_gmra`
--
ALTER TABLE `transaksi_gmra`
  ADD CONSTRAINT `transaksi_gmra_ibfk_1` FOREIGN KEY (`id_file_transaksi`) REFERENCES `file_transaksi` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
