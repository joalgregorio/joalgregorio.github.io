-- MySQL dump 10.13  Distrib 8.0.0-dmr, for osx10.11 (x86_64)
--
-- Host: localhost    Database: ayala_parking
-- ------------------------------------------------------
-- Server version	8.0.0-dmr

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `parking_logs`
--

DROP TABLE IF EXISTS `parking_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parking_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parking_slot` int(2) NOT NULL,
  `plate_number` varchar(8) NOT NULL,
  `time_in` datetime NOT NULL,
  `time_out` datetime DEFAULT NULL,
  `charge` int(11) DEFAULT NULL,
  `prev_paid` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parking_logs`
--

LOCK TABLES `parking_logs` WRITE;
/*!40000 ALTER TABLE `parking_logs` DISABLE KEYS */;
INSERT INTO `parking_logs` VALUES (1,7,'CNT 1111','2022-03-15 08:00:00','2022-03-15 09:00:00',40,0),(2,7,'CNT 1111','2022-03-15 08:00:00','2022-03-16 12:00:00',5200,40),(3,7,'CNT 1111','2022-03-15 08:00:00','2022-03-16 18:00:00',360,5240),(4,7,'CNT 1111','2022-03-17 12:30:00','2022-03-17 18:00:00',220,0),(5,7,'NCT 1111','2022-03-16 22:13:06',NULL,NULL,0);
/*!40000 ALTER TABLE `parking_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parking_rates`
--

DROP TABLE IF EXISTS `parking_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parking_rates` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `slot_type` int(3) NOT NULL,
  `rate_per_hour` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slot_type` (`slot_type`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parking_rates`
--

LOCK TABLES `parking_rates` WRITE;
/*!40000 ALTER TABLE `parking_rates` DISABLE KEYS */;
INSERT INTO `parking_rates` VALUES (16,0,20),(17,1,60),(19,2,100);
/*!40000 ALTER TABLE `parking_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parking_slots`
--

DROP TABLE IF EXISTS `parking_slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parking_slots` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(5) NOT NULL,
  `gate_distance` varchar(12) NOT NULL,
  `slot_type` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parking_slots`
--

LOCK TABLES `parking_slots` WRITE;
/*!40000 ALTER TABLE `parking_slots` DISABLE KEYS */;
INSERT INTO `parking_slots` VALUES (1,'SP1','5,6,1',0),(2,'SP2','4,7,2',0),(3,'SP3','6,5,1',0),(4,'SP4','7,4,2',0),(5,'MP1','1,4,5',1),(6,'MP2','2,5,4',1),(7,'MP3','3,6,3',1),(8,'MP4','4,1,5',1),(9,'MP5','5,2,4',1),(10,'MP6','6,3,3',1),(11,'LP1','1,3,6',2),(12,'LP2','2,2,7',2),(13,'LP3','3,1,6',2);
/*!40000 ALTER TABLE `parking_slots` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-03-16 22:26:32
