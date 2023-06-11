-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: MYSQL_DATABASE
-- ------------------------------------------------------
-- Server version	8.0.32

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
-- Table structure for table `dropdown_lookup_table`
--

DROP TABLE IF EXISTS `dropdown_lookup_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dropdown_lookup_table` (
  `ID` mediumint NOT NULL AUTO_INCREMENT,
  `DEL` bit(1) NOT NULL DEFAULT b'0',
  `TEXT` char(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dropdown_lookup_table`
--

LOCK TABLES `dropdown_lookup_table` WRITE;
/*!40000 ALTER TABLE `dropdown_lookup_table` DISABLE KEYS */;
INSERT INTO `dropdown_lookup_table` VALUES (1,_binary '\0','ONE'),(2,_binary '\0','TWO'),(3,_binary '\0','THREE'),(4,_binary '\0','FOUR'),(5,_binary '\0','FIVE'),(6,_binary '\0','SIX'),(7,_binary '\0','SEVEN'),(8,_binary '\0','EIGHT'),(9,_binary '\0','NINE');
/*!40000 ALTER TABLE `dropdown_lookup_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `root_table`
--

DROP TABLE IF EXISTS `root_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `root_table` (
  `ID` mediumint NOT NULL AUTO_INCREMENT,
  `DEL` bit(1) NOT NULL DEFAULT b'0',
  `TEXT` char(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `CHECKBOX` bit(1) NOT NULL DEFAULT b'0',
  `REF_DROPDOWN` mediumint DEFAULT NULL,
  `LINK` varchar(2083) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `LINK_BUTTON` varchar(2083) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `DATE` date DEFAULT NULL,
  `DATETIME` datetime DEFAULT NULL,
  `COLOR` varchar(7) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `EMAIL` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `root_table`
--

LOCK TABLES `root_table` WRITE;
/*!40000 ALTER TABLE `root_table` DISABLE KEYS */;
INSERT INTO `root_table` VALUES (1,_binary '\0','ALPHA',_binary '\0',1,'https://stackoverflow.com/questions/219569/best-database-field-type-for-a-url','https://stackoverflow.com/questions/219569/best-database-field-type-for-a-url','2022-06-17','2022-06-17 00:00:00','#ff0000','info@dwuty.de '),(2,_binary '\0','BRAVO',_binary '\0',2,'https://packagist.org/packages/datatableswebutility/dwuty','https://packagist.org/packages/datatableswebutility/dwuty','2022-06-23','2022-06-23 12:57:36','#00ff1e','abuse@dwuty.de'),(3,_binary '\0','CHARLIE',_binary '\0',3,'http://datatableswebutility.com/','http://datatableswebutility.com/','2022-06-29','2022-06-30 01:55:12','#4f6392','postmaster@dwuty.de'),(4,_binary '\0','DELTA',_binary '\0',4,'http://datatableswebutility.de','http://datatableswebutility.de','2022-07-05','2022-07-06 14:52:48',NULL,'security@dwuty.de'),(5,_binary '\0','ECHO',_binary '',5,'http://datatableswebutility.net','http://datatableswebutility.net','2022-07-11','2022-07-13 03:50:24',NULL,'info@datatableswebutility.de'),(6,_binary '\0','FOXTROT',_binary '\0',6,'http://dwuty.com','http://dwuty.com','2022-07-17','2022-07-19 16:48:00',NULL,'abuse@datatableswebutility.de'),(7,_binary '\0','GOLF',_binary '\0',7,'http://dwuty.de','http://dwuty.de','2022-07-23','2022-07-26 05:45:36',NULL,'postmaster@datatableswebutility.de'),(8,_binary '\0','HOTEL',_binary '\0',8,'http://dwuty.net','http://dwuty.net','2022-07-29','2022-08-01 18:43:12',NULL,'security@datatableswebutility.de'),(9,_binary '\0','INDIA',_binary '\0',9,NULL,NULL,'2022-08-04','2022-08-08 07:40:48',NULL,NULL),(10,_binary '\0','JULIETT',_binary '',8,NULL,NULL,'2022-08-10','2022-08-14 20:38:24',NULL,NULL),(11,_binary '\0','KILO',_binary '',7,NULL,NULL,'2022-08-16','2022-08-21 09:36:00',NULL,NULL),(12,_binary '\0','LIMA',_binary '\0',6,NULL,NULL,'2022-08-22','2022-08-27 22:33:36',NULL,NULL),(13,_binary '\0','MIKE',_binary '',5,NULL,NULL,'2022-08-28','2022-09-03 11:31:12',NULL,NULL),(14,_binary '\0','NOVEMBER',_binary '\0',4,NULL,NULL,'2022-09-03','2022-09-10 00:28:48',NULL,NULL),(15,_binary '\0','OSCAR',_binary '\0',3,NULL,NULL,'2022-09-09','2022-09-16 13:26:24',NULL,NULL),(16,_binary '\0','PAPA',_binary '\0',2,NULL,NULL,'2022-09-15','2022-09-23 02:24:00',NULL,NULL),(17,_binary '\0','QUEBEC',_binary '\0',1,NULL,NULL,'2022-09-23','2022-09-29 15:21:36',NULL,NULL),(18,_binary '\0','ROMEO',_binary '\0',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(19,_binary '\0','SIERRA',_binary '\0',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(20,_binary '\0','TANGO',_binary '\0',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(21,_binary '\0','UNIFORM',_binary '\0',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(22,_binary '\0','VICTOR',_binary '\0',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(23,_binary '\0','WHISKEY',_binary '\0',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(24,_binary '\0','XRAY',_binary '\0',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(25,_binary '\0','YANKEE',_binary '\0',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(26,_binary '\0','ZULU',_binary '\0',NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `root_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dropdown_multi_lookup_table`
--

DROP TABLE IF EXISTS `dropdown_multi_lookup_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dropdown_multi_lookup_table` (
  `ID` mediumint NOT NULL AUTO_INCREMENT,
  `DEL` bit(1) NOT NULL DEFAULT b'0',
  `TEXT` char(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dropdown_multi_lookup_table`
--

LOCK TABLES `dropdown_multi_lookup_table` WRITE;
/*!40000 ALTER TABLE `dropdown_multi_lookup_table` DISABLE KEYS */;
INSERT INTO `dropdown_multi_lookup_table` VALUES (1,_binary '\0','z&eacute;ro'),(2,_binary '\0','un'),(3,_binary '\0','deux'),(4,_binary '\0','trois'),(5,_binary '\0','quatre'),(6,_binary '\0','cinq'),(7,_binary '\0','six'),(8,_binary '\0','sept'),(9,_binary '\0','huit'),(10,_binary '\0','neuf'),(11,_binary '\0','dix'),(12,_binary '\0','onze'),(13,_binary '\0','douze'),(14,_binary '\0','treize'),(15,_binary '\0','quatorze'),(16,_binary '\0','quinze'),(17,_binary '\0','seize'),(18,_binary '\0','dix-sept'),(19,_binary '\0','dix-huit'),(20,_binary '\0','dix-neuf'),(21,_binary '\0','vingt');
/*!40000 ALTER TABLE `dropdown_multi_lookup_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_root_ref_dropdown_multi_table`
--

DROP TABLE IF EXISTS `ref_root_ref_dropdown_multi_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ref_root_ref_dropdown_multi_table` (
  `ID` mediumint NOT NULL AUTO_INCREMENT,
  `DEL` bit(1) NOT NULL DEFAULT b'0',
  `REF_ROOT` mediumint NOT NULL,
  `REF_DROPDOWN_MULTI` mediumint NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_root_ref_dropdown_multi_table`
--

LOCK TABLES `ref_root_ref_dropdown_multi_table` WRITE;
/*!40000 ALTER TABLE `ref_root_ref_dropdown_multi_table` DISABLE KEYS */;
INSERT INTO `ref_root_ref_dropdown_multi_table` VALUES (1,_binary '\0',1,1),(2,_binary '\0',1,2),(3,_binary '\0',1,3),(4,_binary '\0',1,4),(5,_binary '\0',1,5),(6,_binary '\0',2,6),(7,_binary '\0',2,7),(8,_binary '\0',2,8),(9,_binary '\0',3,9),(10,_binary '\0',3,10),(11,_binary '\0',4,11),(12,_binary '\0',5,12),(13,_binary '\0',5,13),(14,_binary '\0',6,14),(15,_binary '\0',6,15),(16,_binary '\0',6,16),(17,_binary '\0',7,17),(18,_binary '\0',7,18),(19,_binary '\0',7,19),(20,_binary '\0',7,20);
/*!40000 ALTER TABLE `ref_root_ref_dropdown_multi_table` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-03-21 16:24:15
