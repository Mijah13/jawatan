-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: localhost    Database: fasiliti
-- ------------------------------------------------------
-- Server version	8.0.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `asrama`
--

DROP TABLE IF EXISTS `asrama`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asrama` (
  `id` int NOT NULL AUTO_INCREMENT,
  `blok` varchar(10) NOT NULL,
  `aras` int NOT NULL,
  `no_asrama` varchar(20) NOT NULL,
  `status_asrama` int NOT NULL COMMENT '0 = kosong, 1 = sedang dibersihkan, 2 = simpanan, 3 = rosak, 4 = risiko, 5 = sedang dibaiki',
  `kelamin` int NOT NULL COMMENT '0 = lelaki, 1 = perempuan, 2 = lelaki/perempuan',
  `jenis_asrama_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jenis_asrama_id` (`jenis_asrama_id`),
  CONSTRAINT `fk_asrama_jenis_asrama` FOREIGN KEY (`jenis_asrama_id`) REFERENCES `jenis_asrama` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asrama`
--

LOCK TABLES `asrama` WRITE;
/*!40000 ALTER TABLE `asrama` DISABLE KEYS */;
INSERT INTO `asrama` VALUES (2,'R',4,'29',1,1,1),(3,'R',4,'30',0,1,1),(4,'R',4,'03',0,2,2),(5,'R',4,'04',0,2,2),(6,'R',3,'07',0,0,3),(7,'R',3,'08',1,1,3),(8,'R',2,'20',0,1,4),(9,'R',2,'21',0,1,4),(10,'K',1,'10',0,0,5),(11,'N',0,'04',1,2,5),(12,'M',3,'01',0,0,5),(13,'K',2,'01',0,0,5),(14,'R',2,'31',0,1,4);
/*!40000 ALTER TABLE `asrama` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fasiliti`
--

DROP TABLE IF EXISTS `fasiliti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fasiliti` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_fasiliti` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `kadar_sewa_perJam` decimal(10,0) DEFAULT NULL,
  `kadar_sewa_perHari` decimal(10,0) DEFAULT NULL,
  `kadar_sewa_perJamSiang` decimal(10,0) DEFAULT NULL,
  `kadar_sewa_perJamMalam` decimal(10,0) DEFAULT NULL,
  `fasiliti_status` int NOT NULL COMMENT '0 = kosong, 1 = disimpan, 2 = rosak, 3 = sedang dibaiki',
  `gambar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fasiliti`
--

LOCK TABLES `fasiliti` WRITE;
/*!40000 ALTER TABLE `fasiliti` DISABLE KEYS */;
INSERT INTO `fasiliti` VALUES (1,'Dewan Besar','Dilengkapi dengan 2 buah gelanggang badminton dan kemudahan rekreasi yang lain. \r\nKapasiti: 500 orang (Kegunaan kerusi sahaja)\r\n              200 0rang (Kegunaan kerusi dan Meja).\r\nLokasi: CIAST, Blok B',125,1000,NULL,NULL,0,'67a43ce521c1c.jpg'),(2,'Konkos A','Ruang terbuka serbaguna.\r\nKapasiti: 200\r\nLokasi: Blok C',50,400,NULL,NULL,0,'677df9a3f0c44.jpeg'),(3,'Konkos B','Ruang terbuka serbaguna\r\nKapasiti: 200',40,320,NULL,NULL,0,'67a43e95aa661.jpg'),(4,'Gelanggang Takraw','Kapasiti: 12',NULL,NULL,10,30,0,'67a43eb147830.jpg'),(6,'Gelanggang Futsal','Kapasiti: 12',NULL,NULL,20,30,0,'67a43ec6d0219.jpg'),(7,'Gelanggang Tenis A & B','Bilangan: 2\r\nKapasiti: 12',NULL,NULL,10,30,0,'67a43edd6f7d6.jpg'),(8,'Bilik Kuliah 11','Kapasiti: 30 orang\r\nKelengkapan: penghawa dingin, projektor, kerusi dan meja.',40,NULL,NULL,NULL,0,'67ac3a91313f5.jpeg'),(9,'Bilik Kuliah 12','Bilangan: 1\r\nKapasiti: 40\r\nKelengkapan: penghawa dingin, projektor, kerusi dan meja.',50,NULL,NULL,NULL,0,'6776467c8a592.jpeg'),(10,'Bilik Bijaksana','Kapasiti: 60',65,NULL,NULL,NULL,0,'67a4543ab91cd.jpg'),(11,'Bilik Mesyuarat Terbilang','TUJUAN SEMINAR,MESYUARAT,TAKLIMAT\r\nKapasiti: 20',35,NULL,NULL,NULL,0,'67a450f1859a8.jpg'),(12,'Bilik Gemilang','TUJUAN SEMINAR, BENGKEL, TAKLIMAT, MESYUARAT\r\nKapasiti: 20',45,NULL,NULL,NULL,0,'67a4407b57dc1.jpg'),(13,'Asrama','5 jenis Bilik bagi asrama iaitu 1 darinya bilik bagi pelajar CIAST dan selebihnya merupakan bilik bagi penginap luar bagi tujuan peribadi atau berkursus.\r\nKapasiti: 441',NULL,NULL,NULL,NULL,0,'67a870c9ec38d.jpg');
/*!40000 ALTER TABLE `fasiliti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jenis_asrama`
--

DROP TABLE IF EXISTS `jenis_asrama`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_asrama` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jenis_bilik` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `kadar_sewa` decimal(10,0) NOT NULL,
  `asrama_id` int NOT NULL DEFAULT '13',
  `gambar` text,
  PRIMARY KEY (`id`),
  KEY `amaun_kadar` (`asrama_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jenis_asrama`
--

LOCK TABLES `jenis_asrama` WRITE;
/*!40000 ALTER TABLE `jenis_asrama` DISABLE KEYS */;
INSERT INTO `jenis_asrama` VALUES (1,'Bilik Eksekutif 1 katil Single','Set katil Super Single \r\nDilengkapi dengan :\r\nTV\r\nPenghawa dingin \r\nKabinet makanan\r\nPeti sejuk\r\nBilik air',50,13,'[\"67bfc9f89963d.jpg\",\"67bfc9f89baf8.jpg\",\"67bfca02912d8.jpg\",\"67bfca0294cba.jpg\"]'),(2,'Bilik Eksekutif 1 katil Queen','Set katil Super Queen\r\nDilengkapi dengan : \r\nTV\r\nKabinet makanan\r\nAlmari Baju\r\nPeti Sejuk\r\nBilik air ',70,13,'[\"67bfcb91cd614.jpg\",\"67bfcb91ce85c.jpg\",\"67bfcb9bec3f0.jpg\",\"67bfcb9beea57.jpg\",\"67bfcbaba2a54.jpg\",\"67bfcbaba64dd.jpg\"]'),(3,'Bilik Asrama (aircond) 2 katil Single','Set 2 katil Single\r\nDilengkapi dengan :\r\n2 meja belajar\r\n2 Almari Baju\r\n1 Kipas\r\n1 Penghawa Dingin\r\n1 Bilik Air',35,13,'[\"67bfd1c9626c9.jpg\",\"67bfd1c964487.jpg\",\"67bfd1c965d50.jpg\",\"67bfd1c9675c4.jpg\"]'),(4,'Bilik Asrama 2 katil Single','Set 2 katil Single\r\nDilengkapi dengan :\r\n2 meja belajar\r\n2 Almari Baju\r\n1 Kipas\r\n1 Bilik Air',25,13,'[\"67bfd2f5d6e9e.jpg\",\"67bfd2f5defe5.jpg\",\"67bfd2f5e0a3b.jpg\",\"67bfd2f5e1a19.jpg\",\"67bfd2f5e2df4.jpg\"]'),(5,'Bilik Asrama Pelajar','Set katil Single\r\nDilengkapi dengan :\r\n1 meja belajar\r\n1 Almari Baju\r\n1 Kipas\r\n1 Bilik Air',25,13,'[\"67bfdc2702317.jpg\",\"67bfdc2703391.jpg\",\"67bfdc2704cc5.jpg\"]');
/*!40000 ALTER TABLE `jenis_asrama` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penginap_kategori`
--

DROP TABLE IF EXISTS `penginap_kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penginap_kategori` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jenis_penginap` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penginap_kategori`
--

LOCK TABLES `penginap_kategori` WRITE;
/*!40000 ALTER TABLE `penginap_kategori` DISABLE KEYS */;
INSERT INTO `penginap_kategori` VALUES (1,'Pelajar'),(2,'Penginap Persendirian'),(3,'Penginap Berkumpulan');
/*!40000 ALTER TABLE `penginap_kategori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tempah_asrama`
--

DROP TABLE IF EXISTS `tempah_asrama`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tempah_asrama` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_asrama` int DEFAULT NULL,
  `user_id` int NOT NULL,
  `jenis_penginap` int NOT NULL,
  `no_kp_pemohon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `agensi_pemohon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tujuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tarikh_masuk` date DEFAULT NULL,
  `tarikh_keluar` date DEFAULT NULL,
  `no_tel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `jenis_bilik` int DEFAULT NULL,
  `surat_sokongan` blob,
  `no_matrik_pemohon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `kod_kursus` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sesi_batch` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT NULL,
  `masalah_kesihatan` varchar(255) DEFAULT NULL,
  `jantina` int DEFAULT NULL,
  `nama_penginap_1` varchar(255) DEFAULT NULL,
  `email_penginap_1` varchar(255) DEFAULT NULL,
  `no_tel_penginap_1` varchar(255) DEFAULT NULL,
  `alamat_penginap_1` varchar(255) DEFAULT NULL,
  `nama_penginap_2` varchar(255) DEFAULT NULL,
  `email_penginap_2` varchar(255) DEFAULT NULL,
  `no_tel_penginap_2` varchar(255) DEFAULT NULL,
  `alamat_penginap_2` varchar(255) DEFAULT NULL,
  `disokong_oleh` int DEFAULT NULL,
  `status_tempahan_adminKemudahan` int DEFAULT '0' COMMENT '0 = belum disemak, 1 = sedang diproses, 2 = menunggu bayaran, 3 = diluluskan, 4 = dibatalkan 	',
  `status_pembayaran` int DEFAULT '0' COMMENT '0 = Belum disemak, 1 = Tidak Berbayar,  2 = Berbayar, 3 = Telah Dibayar\r\n',
  `diluluskan_oleh` int DEFAULT NULL,
  `status_tempahan_pelulus` int DEFAULT NULL COMMENT '0 = belum disemak, 1 = sedang diproses, 2 = lulus, 3 = batal',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `jenis_bilik` (`jenis_bilik`),
  KEY `fk_bilik` (`id_asrama`),
  KEY `user_ta` (`user_id`),
  KEY `fk_tempah_asrama_disahkan_oleh` (`disokong_oleh`),
  KEY `diluluskan_oleh` (`diluluskan_oleh`),
  CONSTRAINT `fk_bilik` FOREIGN KEY (`id_asrama`) REFERENCES `asrama` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_jenis_bilik` FOREIGN KEY (`jenis_bilik`) REFERENCES `jenis_asrama` (`id`),
  CONSTRAINT `fk_tempah_asrama_diluluskan_oleh` FOREIGN KEY (`diluluskan_oleh`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_tempah_asrama_disahkan_oleh` FOREIGN KEY (`disokong_oleh`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `user_ta` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tempah_asrama`
--

LOCK TABLES `tempah_asrama` WRITE;
/*!40000 ALTER TABLE `tempah_asrama` DISABLE KEYS */;
INSERT INTO `tempah_asrama` VALUES (27,2,8,2,'760121085625','xyz','seminar','2024-12-22','2024-12-24','0162387402','sepang','amira@gmail.com',1,'',NULL,NULL,NULL,0,NULL,1,'','','','','','','','',NULL,0,2,28,0,'2025-02-14 01:36:18','2025-02-23 07:42:43'),(28,4,8,2,'041006010148','bb','bb','2024-12-25','2024-12-26','0115896423','sepg','amira@gmail.com',2,'',NULL,NULL,NULL,0,NULL,1,'','','','','','','','',NULL,0,2,28,0,'2025-02-14 01:36:18','2025-02-23 07:42:43'),(29,6,8,2,'0526631458','VV','V','2024-12-20','2024-12-21','0162389125','XX','amira@gmail.com',3,'',NULL,NULL,NULL,0,NULL,0,'','','','','','','','',NULL,0,2,28,0,'2025-02-14 01:36:18','2025-02-23 07:42:43'),(31,8,8,2,'0526631458','o','o','2024-12-25','2024-12-26','0162387402','O','amira@gmail.com',4,_binary '6764efab00ce7.pdf',NULL,NULL,NULL,0,NULL,1,'','','','','','','','',NULL,0,1,28,0,'2025-02-14 01:36:18','2025-02-23 07:42:43'),(33,4,8,2,'0526631458','YY','YY','2025-01-08','2025-01-09','0162389402','sepang','amira@gmail.com',2,'',NULL,NULL,NULL,0,NULL,0,'','','','','','','','',NULL,0,2,28,0,'2025-02-14 01:36:18','2025-02-23 07:42:43'),(34,2,11,2,'0526631458','xx','xx','2025-01-07','2025-01-08','0158963214','jangkang','stydhsv@gmail.com',1,'',NULL,NULL,NULL,NULL,NULL,0,'','','','','','','','',NULL,0,2,28,0,'2025-02-14 01:36:18','2025-02-23 07:42:43'),(38,4,22,2,'0526631458','CIAST','KURSUS','2025-03-05','2025-03-07','0158963214','SEPANG','amiranjiha@gmail.com',2,'',NULL,NULL,NULL,NULL,NULL,1,'','','','','','','','',6,3,2,28,2,'2025-02-14 01:36:18','2025-02-23 07:42:43'),(40,6,22,2,'0526631458','yy','yy','2025-01-16','2025-01-17','0162389402','hh','amiranjiha@gmail.com',3,'',NULL,NULL,NULL,NULL,NULL,1,'','','','','','','','',6,2,2,28,2,'2025-02-14 01:36:18','2025-02-23 07:42:43'),(43,12,6,1,'0513365896','-','Pelajar CIAST','2025-02-16','2025-04-17','0162389402','','munchkinjiii@gmail.com',5,'','17ddt22f1058','','',NULL,'',1,'','','','','','','','',NULL,1,0,28,NULL,'2025-02-14 01:57:45','2025-02-23 07:42:43'),(46,6,44,2,'04006010148','CIAST','bermalam','2025-02-15','2025-02-16','01155867402','NHN','amiranjiha@ciast.gov.my',3,'',NULL,NULL,NULL,NULL,NULL,1,'','','','','','','','',NULL,0,0,28,NULL,'2025-02-14 03:01:30','2025-02-23 07:42:43'),(47,5,44,2,'04006010148','CIAST','bermalam','2025-02-15','2025-02-16','01155867402','RGR','amiranjiha@ciast.gov.my',2,'',NULL,NULL,NULL,NULL,NULL,0,'','','','','','','','',NULL,1,0,28,NULL,'2025-02-14 03:02:03','2025-02-24 04:38:23'),(48,5,22,2,'021006010148','tnb','CC','2025-02-18','2025-02-19','0162387402','nn','amiranjiha@gmail.com',2,'',NULL,NULL,NULL,NULL,NULL,0,'','','','','','','','',NULL,1,0,28,NULL,'2025-02-14 08:19:57','2025-02-24 02:26:21'),(49,5,22,2,'011006010148','TNB','BERMALAM','2025-02-21','2025-02-22','0162389125','SSSSSSSSS','amiranjiha@gmail.com',2,'',NULL,NULL,NULL,NULL,NULL,1,'','','','','','','','',6,2,2,28,2,'2025-02-16 13:15:02','2025-02-25 07:21:54'),(51,3,44,2,'0105262525','PSIS','KURSUS','2025-03-03','2025-03-04','0162389125','yyyuooooo','amiranjiha@ciast.gov.my',1,'',NULL,NULL,NULL,NULL,NULL,1,'','','','','','','','',6,4,1,28,3,'2025-02-20 08:04:03','2025-02-25 09:25:18'),(52,8,22,2,'041006010148','PSISSSS','PANTAUAN','2025-03-04','2025-03-06','0115896423','SBBRMM','amiranjiha@gmail.com',4,_binary '67b82a6fd16bc.pdf',NULL,NULL,NULL,NULL,NULL,1,'','','','','','','','',NULL,1,0,28,NULL,'2025-02-21 07:25:35','2025-02-25 02:10:34'),(53,NULL,44,2,'0','CIASTTTTTTTTT','BERMALAMMMMM','2025-02-26','2025-02-27','','','amiranjiha@ciast.gov.my',1,'',NULL,NULL,NULL,NULL,NULL,NULL,'','','','','','','','',NULL,0,0,NULL,NULL,'2025-02-24 04:05:36','2025-02-24 07:31:47'),(54,3,44,2,'0526631458','CIAST','BERMALAM','2025-02-26','2025-02-27','0115896423','SHAH ALAM','amiranjiha@ciast.gov.my',1,'',NULL,NULL,NULL,NULL,NULL,1,'','','','','','','','',6,4,2,28,3,'2025-02-24 04:06:13','2025-02-26 01:29:17'),(55,9,22,2,'041006010148','tiada','bermalam saja','2025-02-28','2025-03-01','01155867402','SEPANG','amiranjiha@gmail.com',4,'',NULL,NULL,NULL,NULL,NULL,1,'','','','','','','','',6,2,2,28,2,'2025-02-25 03:01:31','2025-02-26 01:29:04'),(56,3,44,2,'0526631458','CC','BERMALAM','2025-02-28','2025-03-01','01155867402','mmmmmmmmmmmmm','amiranjiha@ciast.gov.my',1,'',NULL,NULL,NULL,NULL,NULL,1,'','','','','','','','',6,3,1,28,NULL,'2025-02-26 02:22:07','2025-02-26 03:41:48');
/*!40000 ALTER TABLE `tempah_asrama` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tempah_fasiliti`
--

DROP TABLE IF EXISTS `tempah_fasiliti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tempah_fasiliti` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fasiliti_id` int DEFAULT NULL,
  `user_id` int NOT NULL,
  `no_kp_pemohon` varchar(255) NOT NULL,
  `agensi_pemohon` varchar(255) NOT NULL,
  `tujuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tarikh_masuk` date NOT NULL,
  `tarikh_keluar` date NOT NULL,
  `no_tel` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tempoh` varchar(50) NOT NULL,
  `jangkaan_hadirin` int DEFAULT NULL,
  `peralatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `lain_peralatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `surat_sokongan` blob,
  `disahkan_oleh` int DEFAULT NULL,
  `status_tempahan_adminKemudahan` int DEFAULT NULL COMMENT '0 = belum disemak, 1 = sedang diproses, 2 = Menunggu bayaran, 3 = Diluluskan, 4 = dibatalkan',
  `status_pembayaran` int NOT NULL DEFAULT '0' COMMENT '0 = Belum disemak, 1 = Tidak Diperlukan, 2 = Diperlukan, 3 =Telah Dibayar ',
  `status_tempahan_pelulus` int DEFAULT NULL COMMENT '0 = belum disemak, 1 = sedang diproses, 2 = lulus, 3 = batal ',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `user_tf` (`user_id`),
  KEY `fasiliti_id` (`fasiliti_id`),
  KEY `disahkan_oleh` (`disahkan_oleh`),
  CONSTRAINT `fk_tempah_fasiliti_disahkan_oleh` FOREIGN KEY (`disahkan_oleh`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `tempah_fasiliti_ibfk_1` FOREIGN KEY (`fasiliti_id`) REFERENCES `fasiliti` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_tf` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tempah_fasiliti`
--

LOCK TABLES `tempah_fasiliti` WRITE;
/*!40000 ALTER TABLE `tempah_fasiliti` DISABLE KEYS */;
INSERT INTO `tempah_fasiliti` VALUES (11,2,11,'121130101127','XX','XX','2025-02-21','2025-02-22','0145856402','sana sini','stydhsv@gmail.com','sesiPagiPetang',50,'P.A sistem,Mikrofon,Skrin Projektor','','',6,1,0,NULL,'2025-02-05 02:13:09','2025-02-13 02:06:32'),(14,7,19,'121130101127','cc','cc','2025-01-14','2025-01-14','0145856402','cc','amira.sistemfasiliti@ciast.edu.my','sesiMalam',20,'Mikrofon','','',NULL,0,0,NULL,'2025-02-05 02:13:09','2025-02-05 02:13:09'),(16,1,19,'121130101127','CC','CC','2025-01-13','2025-01-14','0145856402','CC','amira.sistemfasiliti@ciast.edu.my','satuHari',200,NULL,'','',NULL,0,0,NULL,'2025-02-05 02:13:09','2025-02-05 02:13:09'),(17,1,6,'121130101127','XX','mm','2025-01-06','2025-01-16','0145856402','mm','munchkinjiii@gmail.com','satuHari',200,'P.A sistem,Mikrofon,Skrin Projektor','',_binary '6775f4f73fe47.jpeg',NULL,1,0,NULL,'2025-02-05 02:13:09','2025-02-05 02:13:09'),(20,1,22,'121130101127','CIAST','JAMUAN','2025-01-20','2025-01-21','0145856402','SHAH ALAM','amiranjiha@gmail.com','satuHari',500,'P.A sistem,Mikrofon','',_binary '677744b8e4c85.pdf',6,3,1,2,'2025-02-05 02:13:09','2025-02-05 02:13:09'),(21,3,22,'121130101127','XX','MAKAN','2025-01-31','2025-01-31','0145856402','CIAST','amiranjiha@gmail.com','sesiPagiPetang',200,NULL,'','',6,3,2,2,'2025-02-05 02:13:09','2025-02-05 02:13:09'),(22,4,22,'121130101127','XX','XX','2025-02-03','2025-02-03','0145856402','SHAH ALAM','amiranjiha@gmail.com','sesiMalam',20,NULL,'','',6,1,2,1,'2025-02-05 02:13:09','2025-02-05 02:13:09'),(23,6,22,'121130101127','XX','MAIN','2025-02-11','2025-02-11','0145856402','SHAH ALAM','amiranjiha@gmail.com','sesiMalam',20,NULL,'','',6,3,2,2,'2025-02-05 02:13:09','2025-02-05 02:13:09'),(24,10,22,'121130101127','dewan makan','MEETING','2025-02-12','2025-02-12','0145856402','CIAST','amiranjiha@gmail.com','sesiPagi',10,'P.A sistem,Mikrofon,Skrin Projektor','','',6,3,2,2,'2025-02-05 02:13:09','2025-02-05 02:13:09'),(25,11,22,'121130101127','XX','MEETING','2025-02-13','2025-02-13','0145856402','CIAST','amiranjiha@gmail.com','sesiPetang',15,'P.A sistem,Mikrofon,Skrin Projektor','','',6,3,2,2,'2025-02-05 02:13:09','2025-02-05 02:13:09'),(26,8,6,'041006010148','CIAST','BENGKEL','2025-02-06','2025-02-06','01155867402','CIAST','munchkinjiii@gmail.com','sesiPagi',15,'P.A sistem,Mikrofon,Skrin Projektor','','',NULL,1,0,NULL,'2025-02-05 02:13:09','2025-02-05 02:13:09'),(27,9,22,'121130101127','XX','JJ','2025-02-06','2025-02-06','0145856402','JJ','amiranjiha@gmail.com','sesiPagi',2,'P.A sistem,Mikrofon,Skrin Projektor','','',6,4,0,0,'2025-02-05 02:13:09','2025-02-05 04:34:49'),(28,12,11,'121130101127','XX','meeting','2025-02-07','2025-02-07','0145856402','XX','stydhsv@gmail.com','sesiPagiPetang',20,NULL,'','',NULL,1,0,NULL,'2025-02-05 02:13:09','2025-02-05 02:13:09'),(29,9,11,'121130101127','HRJ','PROGRAM','2025-02-08','2025-02-08','0145856402','SIJANGKANG','stydhsv@gmail.com','sesiPagiPetang',25,NULL,'','',6,2,2,2,'2025-02-05 02:19:33','2025-02-05 02:49:26'),(30,4,11,'121130101127','HRJ','main','2025-02-06','2025-02-06','0145856402','jangkang','stydhsv@gmail.com','sesiPagi',11,NULL,'','',6,4,2,3,'2025-02-05 02:51:16','2025-02-07 07:17:06'),(31,6,22,'041006010148','CIAST','SUKAN','2025-02-19','2025-02-19','01155867402','SEPANG','amiranjiha@gmail.com','sesiPetang',20,NULL,'','',6,1,1,1,'2025-02-13 07:08:15','2025-02-13 07:51:40'),(32,3,22,'121130101127','XX','JJ','2025-02-26','2025-02-27','0145856402','gg','amiranjiha@gmail.com','sesiPagi',20,NULL,'','',NULL,1,0,NULL,'2025-02-24 07:04:18','2025-02-24 07:04:23');
/*!40000 ALTER TABLE `tempah_fasiliti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tempahan`
--

DROP TABLE IF EXISTS `tempahan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tempahan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_id` int NOT NULL,
  `info` varchar(100) NOT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tempahan`
--

LOCK TABLES `tempahan` WRITE;
/*!40000 ALTER TABLE `tempahan` DISABLE KEYS */;
INSERT INTO `tempahan` VALUES (1,1,'Test','2024-12-10','2024-12-12'),(2,1,'Majlis','2024-12-17','2024-12-25');
/*!40000 ALTER TABLE `tempahan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `authKey` varchar(255) NOT NULL,
  `accessToken` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `role` int NOT NULL COMMENT '0 = admin sistem, 1 = admin kemudahan, 2 = pelulus, 3 = user luar, 4 = user dalam (ciast/mohr), 5 = student',
  `status` int NOT NULL DEFAULT '0',
  `password_reset_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (6,'admin sistem','munchkinjiii@gmail.com','$2y$13$sF/KVgrIyp5kukO4Hr8CQujXWHXAe7T.MgmKVFbM4cI9Y8TtvGIdC','9MYC5sVmtSxZLVrL-raXyIIFyrBzu53a',NULL,NULL,0,1,''),(8,'ketua admin','amira@gmail.com','$2y$13$62AZiKoa1YTFB8VVjxMa9eiWOigS8/J5/3yzOtQZr79TaZBazLzOW','DkUeS_Rpg1kw3orRh_xOJwTmsMcoTp5I',NULL,'eZiB-X0LDAQkCYaWxtlC7U4D-aIK--Kx_1732890724',6,1,''),(11,'iti','stydhsv@gmail.com','$2y$13$.qY2V3ywWxwiSb8QO7Xa3OCJSED5/mNqJlJ7uINPx/JIrY1pjebQG','68Pz6K_KP4xNhL3aGBjQwLJgILILAdTU',NULL,'H0DTcNMML44FOY3KKit4XiG7YyUQ55V3_1734418956',3,1,''),(19,'admin kemudahan','amira.sistemfasiliti@ciast.edu.my','$2y$13$1ZaVDMNh2aJwNiMcLsc12umzPl.RJumLv.xW8G0BpY/djKRZwLV8K','Nom_3SkvYCv_cXazvehqOMgYe4Czt2EB',NULL,'gvN4mfOOL2FvME44fIvYscG6v1fjcQKl_1735405561',1,1,''),(22,'AMIRA NAJIHA ','amiranjiha@gmail.com','$2y$13$hzkkEx2Y.Vbvw2WB3TbJkO5FZBxKyEQQvUPbFHDkIYLSKCxIuc7PC','x23MC560zwBPzHqcsRSv-rMqaBnnBCfk',NULL,NULL,3,1,NULL),(28,'Pelulus1','miraajiaas@gmail.com','$2y$13$3W.T3X0V0gc28QyXuipI4u3xhte.G2VAYWJQhlZ4xDB8aCpAr3Fy6','FfJbBSsmIGjG6JJ3QM0OHEMULo3RKk_Y',NULL,NULL,2,1,NULL),(44,'amira 55','amiranjiha@ciast.gov.my','$2y$13$n/fHRyKdvCbty7dtMA3Pw.LkXMsf0pf7CAW5K34WlMMiv29zToju.','IjEItqA-RHxeiSSFZBKm6Q_xXw0mKGwC',NULL,NULL,4,1,'LUf7RTxNwNMZ-T6t_f3sozm_ZUWDG3Nn_1740365610'),(45,'Rabia','rabia@ciast.gov.my','$2y$13$ImeH2QMvwufvHOEnkWngFexGRifvsgX36ON5tsm25sKhWr6zH3QSG','lOkCzwf2LdvmPm5xL4DA9NpC1FOm_zTm',NULL,'SBRYvo8EZP1AU6eunt0a-Q1dxYqirRpP_1739429274',4,1,NULL),(46,'student','fasiliti@ciast.edu.my','$2y$13$zDhVK7DAOA9l2iPriQjiD.nFouvO.KLypiRJgRXVoebxHAs60jrjq','BgQsXnmo4KqIJVS21Lckv1o2wGxEVbLB',NULL,NULL,5,1,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-27 15:40:44
