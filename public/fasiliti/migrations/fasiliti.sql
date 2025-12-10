-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 05, 2024 at 03:20 AM
-- Server version: 8.0.36
-- PHP Version: 8.4.0RC2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fasiliti`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `Admin` (
  `admin_id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `admin_kategori_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Admin_kategori`
--

CREATE TABLE `Admin_kategori` (
  `id` int NOT NULL,
  `admin_role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Admin_kategori`
--

INSERT INTO `Admin_kategori` (`id`, `admin_role`) VALUES
(1, 'admin_kemudahan'),
(2, 'admin_kewangan'),
(3, 'admin_sistem');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_asrama`
--

CREATE TABLE `jenis_asrama` (
  `id` int NOT NULL,
  `jenis_bilik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `rental_rate` decimal(10,2) NOT NULL,
  `asrama_id` int NOT NULL
) ;

--
-- Dumping data for table `jenis_asrama`
--

INSERT INTO `jenis_asrama` (`id`, `jenis_bilik`, `rental_rate`, `asrama_id`) VALUES
(1, 'Bilik Eksekutif 1 Katil Single', 50.00, 13),
(2, 'Bilik Eksekutif 1 Katil Queen', 70.00, 13),
(3, 'Bilik Asrama Berhawa Dingin 2 Katil Single', 35.00, 13),
(4, 'Bilik Asrama Tanpa Hawa Dingin 2 Katil Single', 25.00, 13),
(5, 'Asrama Pelajar Sepenuh Masa', 0.00, 13);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_fasiliti`
--

CREATE TABLE `jenis_fasiliti` (
  `id` int NOT NULL,
  `nama_fasiliti` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `kadar_sewa_perJam` decimal(10,2) DEFAULT NULL,
  `kadar_sewa_perHari` decimal(10,2) DEFAULT NULL,
  `kadar_sewa_perJamSiang` decimal(10,2) DEFAULT NULL,
  `kadar_sewa_perJamMalam` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jenis_fasiliti`
--

INSERT INTO `jenis_fasiliti` (`id`, `nama_fasiliti`, `deskripsi`, `kadar_sewa_perJam`, `kadar_sewa_perHari`, `kadar_sewa_perJamSiang`, `kadar_sewa_perJamMalam`) VALUES
(1, 'Dewan Besar', '<strong>Diskripsi</strong>\nDewan Besar CIAST dilengkapi dengan 2 buah gelanggang badminton dan kemudahan rekreasi yang lain. Muatan: - 500 orang (Kegunaan kerusi sahaja) - 200 0rang (Kegunaan kerusi dan Meja) - Kerusi dan meja tidak disertakan Dibuka hanya pada hari bekerja sahaja.\nBilangan: 1\nKapasiti: 255\n<strong>Syarat Tempahan</strong>\nTempahan perlu dibuat dalam tempoh minima 7 hari sebelum aktiviti/program dijalankan. -Bayaran hendaklah dijelaskan 1 minggu sebelum tarikh penggunaan. Bayaran boleh dibuat dengan kiriman wang pos atau draf bank atas nama \"Pengarah CIAST\" atau secara tunai di Unit Kewangan CIAST.Sebarang pengambilan kunci hendaklah dicatit ke  dalam Buku Rekod Kunci, di Unit Kemudahan oleh pihak yang membuat tempahan atau wakilnya dalam waktu pejabat (8.00pagi hingga 4.00petang)Setelah selesai penggunaan dewan hendaklah di dengan kunci dan kunci perlu dikembalikan ke Unit Kemudahan semula dengan menandatangani Buku Rekod Kunci.\n<strong>Hubungi Agensi</strong>\nAlamat: Jalan Petani 19/1, Seksyen 19, 40900 Shah Alam, Selangor Darul Ehsan\nTelefon Pejabat/Bimbit: 03-55438200\nFaks: 03-55438398\nE-mel: info@ciast.gov.my; norhisyam@ciast.gov.my; siti.norbaya@ciast.gov.my\n<strong>Penyelia Fasiliti</strong>\nJuhaini Binti Kamarudin  juhaini@ciast.gov.my\n', 125.00, 1000.00, NULL, NULL),
(2, 'Konkos A', '<strong>Diskripsi</strong>\r\nDisediakan untuk jamuan makan ( Kerusi Meja Tidak Disediakan )\r\nBilangan: 1\r\nKapasiti: 200\r\n<strong>Syarat Tempahan</strong>\r\nPenyewa bertanggungjawab dalam menjaga kebersihan konkos A selepas digunakan.\r\n<strong>Hubungi Agensi</strong>\r\nAlamat: Jalan Petani 19/1, Seksyen 19, 40900 Shah Alam, Selangor Darul Ehsan\r\nTelefon Pejabat/Bimbit: 03-55438200\r\nFaks: 03-55438398\r\nE-mel: info@ciast.gov.my; norhisyam@ciast.gov.my; siti.norbaya@ciast.gov.my\r\n<strong>Penyelia Fasiliti</strong>\r\nJuhaini Binti Kamarudin  juhaini@ciast.gov.my\r\n', 50.00, 1500.00, NULL, NULL),
(3, 'Konkos B', '<strong>Diskripsi</strong>\r\nParkir Kenderaan Berbumbung\r\nBilangan: 1\r\nKapasiti: 200\r\n<strong>Syarat Tempahan</strong>\r\nPenyewa bertanggungjawab dalam menjaga kebersihan konkos B selepas digunakan.\r\n<strong>Hubungi Agensi</strong>\r\nAlamat: Jalan Petani 19/1, Seksyen 19, 40900 Shah Alam, Selangor Darul Ehsan\r\nTelefon Pejabat/Bimbit: 03-55438200\r\nFaks: 03-55438398\r\nE-mel: info@ciast.gov.my; norhisyam@ciast.gov.my; siti.norbaya@ciast.gov.my\r\n<strong>Penyelia Fasiliti</strong>\r\nJuhaini Binti Kamarudin  juhaini@ciast.gov.my\n', 40.00, 320.00, NULL, NULL),
(4, 'Gelanggang Takraw', '<strong>Diskripsi</strong>\r\nTerdapat 1 gelanggang takraw sahaja\r\nBilangan: 1\r\nKapasiti: 12\r\n<strong>Syarat Tempahan</strong>\r\n1. Perlu ditempah seminggu awal sebelum tarikh tempahan.\r\n2. Net dan Raket tidak disediakan.\r\n3. Kegunaan untuk para pelajar dan kakitangan CIAST sahaja.\r\n<strong>Hubungi Agensi</strong>\r\nAlamat: Jalan Petani 19/1, Seksyen 19, 40900 Shah Alam, Selangor Darul Ehsan\r\nTelefon Pejabat/Bimbit: 03-55438200\r\nFaks: 03-55438398\r\nE-mel: info@ciast.gov.my; norhisyam@ciast.gov.my; siti.norbaya@ciast.gov.my\r\n<strong>Penyelia Fasiliti</strong>\r\nJuhaini Binti Kamarudin  juhaini@ciast.gov.my\r\nNurul Najwa Iliani binti Abdul Rahim  najwa.iliani@ciast.gov.my', NULL, NULL, 10.00, 30.00),
(5, 'Gelanggang Badminton', '<strong>Diskripsi</strong>\r\nTerdapat 2 gelanggang badminton sahaja\r\nBilangan: 2\r\nKapasiti: 12\r\n<strong>Syarat Tempahan</strong>\r\n1. Perlu ditempah seminggu awal sebelum tarikh tempahan.\r\n2. Net dan Raket tidak disediakan.\r\n3. Kegunaan untuk para pelajar dan kakitangan CIAST sahaja.\r\n<strong>Hubungi Agensi</strong>\r\nAlamat: Jalan Petani 19/1, Seksyen 19, 40900 Shah Alam, Selangor Darul Ehsan\r\nTelefon Pejabat/Bimbit: 03-55438200\r\nFaks: 03-55438398\r\nE-mel: info@ciast.gov.my; norhisyam@ciast.gov.my; siti.norbaya@ciast.gov.my\r\n<strong>Penyelia Fasiliti</strong>\r\nJuhaini Binti Kamarudin  juhaini@ciast.gov.my\r\nNurul Najwa Iliani binti Abdul Rahim  najwa.iliani@ciast.gov.my', NULL, NULL, 20.00, 30.00),
(6, 'Gelanggang Futsal', '<strong>Diskripsi</strong>\r\nTerdapat 1 gelanggang futsal sahaja \r\nBilangan: 1\r\nKapasiti: 12\r\n<strong>Syarat Tempahan</strong>\r\n1. Perlu ditempah seminggu awal sebelum tarikh tempahan.\r\n2. Net dan Raket tidak disediakan.\r\n3. Kegunaan untuk para pelajar dan kakitangan CIAST sahaja.\r\n<strong>Hubungi Agensi</strong>\r\nAlamat: Jalan Petani 19/1, Seksyen 19, 40900 Shah Alam, Selangor Darul Ehsan\r\nTelefon Pejabat/Bimbit: 03-55438200\r\nFaks: 03-55438398\r\nE-mel: info@ciast.gov.my; norhisyam@ciast.gov.my; siti.norbaya@ciast.gov.my\r\n<strong>Penyelia Fasiliti</strong>\r\nJuhaini Binti Kamarudin  juhaini@ciast.gov.my\r\nNurul Najwa Iliani binti Abdul Rahim  najwa.iliani@ciast.gov.my', NULL, NULL, 20.00, 30.00),
(7, 'Gelanggang Tennis A & B', '<strong>Diskripsi</strong>\r\nTerdapat 2 gelanggang tennis sahaja \r\nBilangan: 2\r\nKapasiti: 12\r\n<strong>Syarat Tempahan</strong>\r\n1. Perlu ditempah seminggu awal sebelum tarikh tempahan.\r\n2. Net dan Raket tidak disediakan.\r\n3. Kegunaan untuk para pelajar dan kakitangan CIAST sahaja.\r\n<strong>Hubungi Agensi</strong>\r\nAlamat: Jalan Petani 19/1, Seksyen 19, 40900 Shah Alam, Selangor Darul Ehsan\r\nTelefon Pejabat/Bimbit: 03-55438200\r\nFaks: 03-55438398\r\nE-mel: info@ciast.gov.my; norhisyam@ciast.gov.my; siti.norbaya@ciast.gov.my\r\n<strong>Penyelia Fasiliti</strong>\r\nJuhaini Binti Kamarudin  juhaini@ciast.gov.my\r\nNurul Najwa Iliani binti Abdul Rahim  najwa.iliani@ciast.gov.my', NULL, NULL, 10.00, 30.00),
(8, 'Bilik Kuliah 11', '<strong>Diskripsi</strong>\r\nBilik Kuliah ini boleh memuatkan 30 orang pada satu masa. Disertakan dengan kemudahan penghawa dingin, projektor, kerusi dan meja.\r\nBilangan: 1\r\nKapasiti: 30\r\n<strong>Syarat Tempahan</strong>\r\nTempahan perlu dibuat dalam tempoh minima 7 hari sebelum aktiviti/program dijalankan.\r\nSebarang pengambilan kunci hendaklah dicatit ke  dalam Buku Rekod Kunci, di Unit Kemudahan oleh pihak yang membuat tempahan atau wakilnya dalam waktu pejabat (8.00pagi hingga 5.00petang)\r\nSetelah selesai penggunaan bilik hendaklah di dengan kunci dan kunci perlu dikembalikan ke Unit Kemudahan semula dengan menandatangani Buku Rekod Kunci.\r\n<strong>Hubungi Agensi</strong>\r\nAlamat: Jalan Petani 19/1, Seksyen 19, 40900 Shah Alam, Selangor Darul Ehsan\r\nTelefon Pejabat/Bimbit: 03-55438200\r\nFaks: 03-55438398\r\nE-mel: info@ciast.gov.my; norhisyam@ciast.gov.my; siti.norbaya@ciast.gov.my\r\n<strong>Penyelia Fasiliti</strong>\r\nJuhaini Binti Kamarudin  juhaini@ciast.gov.my', 40.00, NULL, NULL, NULL),
(9, 'Bilik Kuliah 12', '<strong>Diskripsi</strong>\r\nBilik Kuliah ini boleh memuatkan 40 orang pada satu masa. Disertakan dengan kemudahan penghawa dingin, projektor, kerusi dan meja.\r\nBilangan: 1\r\nKapasiti: 40\r\n<strong>Syarat Tempahan</strong>\r\nTempahan perlu dibuat dalam tempoh minima 7 hari sebelum aktiviti/program dijalankan. Sebarang pengambilan kunci hendaklah dicatit ke  dalam Buku Rekod Kunci, di Unit Kemudahan oleh pihak yang membuat tempahan atau wakilnya. Setelah selesai penggunaan bilik hendaklah di dengan kunci dan kunci perlu dikembalikan ke Unit Kemudahan semula dengan menandatangani Buku Rekod Kunci.\r\n<strong>Hubungi Agensi</strong>\r\nAlamat: Jalan Petani 19/1, Seksyen 19, 40900 Shah Alam, Selangor Darul Ehsan\r\nTelefon Pejabat/Bimbit: 03-55438200\r\nFaks: 03-55438398\r\nE-mel: info@ciast.gov.my; norhisyam@ciast.gov.my; siti.norbaya@ciast.gov.my\r\n<strong>Penyelia Fasiliti</strong>\r\nJuhaini Binti Kamarudin  juhaini@ciast.gov.my', 50.00, NULL, NULL, NULL),
(10, 'Bilik Mesyuarat Bijaksana', '<strong>Diskripsi</strong>\r\nFasiliti boleh memuatkan 60 peserta pada satu masa.\r\nBilangan: 1\r\nKapasiti: 60\r\n<strong>Syarat Tempahan</strong>\r\nTempahan perlu dibuat dalam tempoh minima 7 hari sebelum aktiviti/program dijalankan. Sebarang pengambilan kunci hendaklah dicatit ke  dalam Buku Rekod Kunci, di Unit Kemudahan oleh pihak yang membuat tempahan atau wakilnya. Setelah selesai penggunaan bilik hendaklah di dengan kunci dan kunci perlu dikembalikan ke Unit Kemudahan semula dengan menandatangani Buku Rekod Kunci.\r\n<strong>Hubungi Agensi</strong>\r\nAlamat: Jalan Petani 19/1, Seksyen 19, 40900 Shah Alam, Selangor Darul Ehsan\r\nTelefon Pejabat/Bimbit: 03-55438200\r\nFaks: 03-55438398\r\nE-mel: info@ciast.gov.my; norhisyam@ciast.gov.my; siti.norbaya@ciast.gov.my\r\n<strong>Penyelia Fasiliti</strong>\r\nJuhaini Binti Kamarudin  juhaini@ciast.gov.my', 65.00, NULL, NULL, NULL),
(11, 'Bilik Mesyuarat Terbilang', '<strong>Diskripsi</strong>\r\nTUJUAN SEMINAR,MESYUARAT,TAKLIMAT\r\nBilangan: 1\r\nKapasiti: 20\r\n<strong>Syarat Tempahan</strong>\r\nTempahan perlu dibuat dalam tempoh minima 7 hari sebelum aktiviti/program dijalankan. Sebarang pengambilan kunci hendaklah dicatit ke  dalam Buku Rekod Kunci, di Unit Kemudahan oleh pihak yang membuat tempahan atau wakilnya. Setelah selesai penggunaan bilik hendaklah di dengan kunci dan kunci perlu dikembalikan ke Unit Kemudahan semula dengan menandatangani Buku Rekod Kunci.\r\n<strong>Hubungi Agensi</strong>\r\nAlamat: Jalan Petani 19/1, Seksyen 19, 40900 Shah Alam, Selangor Darul Ehsan\r\nTelefon Pejabat/Bimbit: 03-55438200\r\nFaks: 03-55438398\r\nE-mel: info@ciast.gov.my; norhisyam@ciast.gov.my; siti.norbaya@ciast.gov.my\r\n<strong>Penyelia Fasiliti</strong>\r\nJuhaini Binti Kamarudin  juhaini@ciast.gov.my', 35.00, NULL, NULL, NULL),
(12, 'Bilik Mesyuarat Gemilang', '<strong>Diskripsi</strong>\r\nTUJUAN SEMINAR,MESYUARAT,TAKLIMAT\r\nBilangan: 1\r\nKapasiti: 20\r\n<strong>Syarat Tempahan</strong>\r\nTempahan perlu dibuat dalam tempoh minima 7 hari sebelum aktiviti/program dijalankan. Sebarang pengambilan kunci hendaklah dicatit ke  dalam Buku Rekod Kunci, di Unit Kemudahan oleh pihak yang membuat tempahan atau wakilnya. Setelah selesai penggunaan bilik hendaklah di dengan kunci dan kunci perlu dikembalikan ke Unit Kemudahan semula dengan menandatangani Buku Rekod Kunci.\r\n<strong>Hubungi Agensi</strong>\r\nAlamat: Jalan Petani 19/1, Seksyen 19, 40900 Shah Alam, Selangor Darul Ehsan\r\nTelefon Pejabat/Bimbit: 03-55438200\r\nFaks: 03-55438398\r\nE-mel: info@ciast.gov.my; norhisyam@ciast.gov.my; siti.norbaya@ciast.gov.my\r\n<strong>Penyelia Fasiliti</strong>\r\nJuhaini Binti Kamarudin  juhaini@ciast.gov.my', 45.00, NULL, NULL, NULL),
(13, 'Asrama', '<strong>Diskripsi</strong>\r\nASRAMA\r\nLokasi : BLOK N, BLOK K, & BLOK R, CIAST\r\nKapasiti: 441\r\n<strong>Syarat Tempahan</strong>\r\nTempahan dibuka kepada : pelajar-pelajar dan peserta-peserta kursus. Pembatalan perlu dibuat dalam tempoh minima 3 hari sebelum tarikh tempahan\r\n<strong>Hubungi Agensi</strong>\r\nAlamat: Jalan Petani 19/1, Seksyen 19, 40900 Shah Alam, Selangor Darul Ehsan\r\nTelefon Pejabat/Bimbit: 03-55438420\r\nE-mel: kemudahan@ciast.gov.my\r\nWaktu Operasi: 8.00 pagi – 5.00 Petang\r\n<strong>Penyelia Fasiliti</strong>\r\nTengku Mohd Faizal bin Tengku Mohd Faizul tengku.faizal@ciast.gov.my', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1730172347),
('m241029_031958_add_start_end_time_to_status_fasiliti', 1730689208);

-- --------------------------------------------------------

--
-- Table structure for table `penginap_kategori`
--

CREATE TABLE `penginap_kategori` (
  `id_penginap` int NOT NULL,
  `jenis_penginap` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penginap_kategori`
--

INSERT INTO `penginap_kategori` (`id_penginap`, `jenis_penginap`) VALUES
(1, 'Bilik Eksekutif 1 Katil Single'),
(2, 'Bilik Eksekutif 1 Katil Queen'),
(3, 'Bilik Asrama berhawa dingin 2 Katil Single'),
(4, 'Bilik Asrama tanpa hawa dingin 2 Katil Single');

-- --------------------------------------------------------

--
-- Table structure for table `status_fasiliti`
--

CREATE TABLE `status_fasiliti` (
  `id` int NOT NULL,
  `kekosongan` date NOT NULL,
  `start_time` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `end_time` datetime NOT NULL DEFAULT '2024-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `status_fasiliti`
--

INSERT INTO `status_fasiliti` (`id`, `kekosongan`, `start_time`, `end_time`) VALUES
(1, '2024-10-20', '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
(2, '2024-10-21', '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
(3, '2024-10-22', '2024-01-01 00:00:00', '2024-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tempah`
--

CREATE TABLE `tempah` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `nama_pemohon` varchar(255) NOT NULL,
  `no_kp_pemohon` varchar(255) NOT NULL,
  `agensi_pemohon` varchar(255) DEFAULT NULL,
  `tujuan_nama_kursus` varchar(255) DEFAULT NULL,
  `tarikh_masuk` date NOT NULL,
  `tarikh_keluar` date NOT NULL,
  `no_tel` varchar(255) DEFAULT NULL,
  `alamat` text,
  `email` varchar(255) DEFAULT NULL,
  `jenis_fasiliti` int NOT NULL,
  `jangkaan_hadirin` int DEFAULT NULL,
  `peralatan` json DEFAULT NULL,
  `lain_peralatan` varchar(255) DEFAULT NULL,
  `jenis_penginap` int NOT NULL,
  `kod_kursus` varchar(255) DEFAULT NULL,
  `sesi_batch` varchar(255) DEFAULT NULL,
  `status` enum('berkahwin','bujang') DEFAULT NULL,
  `masalah_kesihatan` text,
  `jenis_bilik` varchar(255) DEFAULT NULL,
  `jantina` enum('Lelaki','Perempuan') DEFAULT NULL,
  `nama_peserta` json DEFAULT NULL,
  `no_kp_peserta` json DEFAULT NULL,
  `no_tel_peserta` json DEFAULT NULL,
  `alamat_peserta` json DEFAULT NULL,
  `email_peserta` json DEFAULT NULL,
  `jenis_bilik_peserta` json DEFAULT NULL,
  `bilangan_lelaki` int DEFAULT NULL,
  `bilangan_perempuan` int DEFAULT NULL,
  `admin_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `authKey` varchar(255) DEFAULT NULL,
  `accessToken` varchar(255) DEFAULT NULL,
  `peranan` int NOT NULL DEFAULT '3'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `email`, `password_hash`, `authKey`, `accessToken`, `peranan`) VALUES
(1, 'jia', 'munchkinjiii@gmail.com', '$2y$13$wRQ2MslkzCk4HI8z0se7T./4L/FxOiBPIXtZEY8MHIeJZeXRH/V86', 'HihsldgB3plnsZpy7w3XzLCT7RNDGbDH', NULL, 3),
(2, 'amira', 'amiranjiha@gmail.com', '$2y$13$HQD4SYeO4CBECq3tl2dgEObfxjSGJDWlqbjHXv8kYbgSEztV3dTm2', 'YXZOmYahO5wqBNE70XFYRmSACYRgAtkl', NULL, 3),
(3, 'meme', 'meme123@gmail.com', '$2y$13$lcaH5XaIP.6hkQKL55xKh.brO2vK/.6xOuSpbRBpic2A15n2zQggq', 'rwXPd21lzXjppNduACQ8UhSUoLHbk9xL', NULL, 3),
(4, 'admin', 'adminSistem@gmail.com', '$2y$13$4mnxEg3fliWl8/AVF5LuIe3jKLYFxcimxEtaIQT3m98w7bc7VxsI2', 'cRSNGbKWQ6xijBmkurE8dZVkNTPOHj3n', NULL, 0),
(5, 'admin', 'adminKemudahan@gmail.com', '$2y$13$iNqOSKBUbtzhNxAX5dfZ.uGi/uMYp4hCtuKQDzjCX5ZRr9kBfsIyC', 'LhkXJLQ0F0caMEoC8joz0u62Va2YbXdc', NULL, 1),
(6, 'admin', 'adminKewangan@gmail.com', '$2y$13$emFqubesfPdCTiWcVM9CVuDMmMXvLVVYsE.9vTmkuVz8WizskzAVO', 'jCoMEOBrbRxqxqNwhbV29S0MBP1mVPXu', NULL, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `admin_kategori_id` (`admin_kategori_id`);

--
-- Indexes for table `Admin_kategori`
--
ALTER TABLE `Admin_kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_asrama`
--
ALTER TABLE `jenis_asrama`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`asrama_id`);

--
-- Indexes for table `jenis_fasiliti`
--
ALTER TABLE `jenis_fasiliti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `penginap_kategori`
--
ALTER TABLE `penginap_kategori`
  ADD PRIMARY KEY (`id_penginap`);

--
-- Indexes for table `status_fasiliti`
--
ALTER TABLE `status_fasiliti`
  ADD PRIMARY KEY (`id`,`kekosongan`);

--
-- Indexes for table `tempah`
--
ALTER TABLE `tempah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tempah_ibfk_1` (`admin_id`),
  ADD KEY `penginap_ketegori_ss` (`jenis_penginap`),
  ADD KEY `userId_fk` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admin`
--
ALTER TABLE `Admin`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Admin_kategori`
--
ALTER TABLE `Admin_kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jenis_asrama`
--
ALTER TABLE `jenis_asrama`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_fasiliti`
--
ALTER TABLE `jenis_fasiliti`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tempah`
--
ALTER TABLE `tempah`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Admin`
--
ALTER TABLE `Admin`
  ADD CONSTRAINT `Admin_ibfk_1` FOREIGN KEY (`admin_kategori_id`) REFERENCES `Admin_kategori` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jenis_asrama`
--
ALTER TABLE `jenis_asrama`
  ADD CONSTRAINT `jenis_asrama_ibfk_1` FOREIGN KEY (`asrama_id`) REFERENCES `jenis_fasiliti` (`id`);

--
-- Constraints for table `status_fasiliti`
--
ALTER TABLE `status_fasiliti`
  ADD CONSTRAINT `fk_facility_type` FOREIGN KEY (`id`) REFERENCES `jenis_fasiliti` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tempah`
--
ALTER TABLE `tempah`
  ADD CONSTRAINT `userId_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
