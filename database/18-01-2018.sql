-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 13, 2018 at 07:50 PM
-- Server version: 5.7.20-0ubuntu0.16.04.1
-- PHP Version: 7.0.22-0ubuntu0.16.04.1

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
-- RELATIONS FOR TABLE `closing_rate`:
--

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
-- RELATIONS FOR TABLE `customer`:
--   `id_file_transaksi`
--       `file_transaksi` -> `id`
--

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
-- RELATIONS FOR TABLE `file_transaksi`:
--

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
-- RELATIONS FOR TABLE `kanwil`:
--

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
-- RELATIONS FOR TABLE `target`:
--

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
-- RELATIONS FOR TABLE `target_kanwil`:
--   `fk_kanwil_id`
--       `kanwil` -> `id`
--

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
-- RELATIONS FOR TABLE `transaksi_bond`:
--   `id_file_transaksi`
--       `file_transaksi` -> `id`
--

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
-- RELATIONS FOR TABLE `transaksi_fx`:
--   `id_file_transaksi`
--       `file_transaksi` -> `id`
--

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
-- RELATIONS FOR TABLE `transaksi_gmra`:
--   `id_file_transaksi`
--       `file_transaksi` -> `id`
--

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
-- AUTO_INCREMENT for table `closing_rate`
--
ALTER TABLE `closing_rate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `file_transaksi`
--
ALTER TABLE `file_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kanwil`
--
ALTER TABLE `kanwil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `target_kanwil`
--
ALTER TABLE `target_kanwil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transaksi_bond`
--
ALTER TABLE `transaksi_bond`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transaksi_fx`
--
ALTER TABLE `transaksi_fx`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transaksi_gmra`
--
ALTER TABLE `transaksi_gmra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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


--
-- Stand-in structure for view `summary_fx`
--
CREATE TABLE `summary_fx` (
`customer` varchar(50)
,`quantity` decimal(52,2)
,`count` bigint(21)
,`year` int(4)
);

-- --------------------------------------------------------

--
-- Structure for view `summary_fx`
--
DROP TABLE IF EXISTS `summary_fx`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `summary_fx`  AS  select `C`.`Nama` AS `customer`,sum(`T`.`EkuivalentUSD`) AS `quantity`,count(`T`.`EkuivalentUSD`) AS `count`,year(`T`.`DealDate`) AS `year` from (`transaksi_fx` `T` join `customer` `C` on((`T`.`CusCod` = `C`.`CusCod`))) where (`T`.`Status` in ('INPUT','SUKSES KREDIT STO','SUKSES SISTEM LAIN','REJECT EC KREDIT')) group by `C`.`Nama`,year(`T`.`DealDate`) ;
