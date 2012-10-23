-- MySQL dump 10.13  Distrib 5.5.24, for debian-linux-gnu (i686)
--
-- Host: asapdb01.cqezga1cxvxz.us-east-1.rds.amazonaws.com    Database: ASAPDB01
-- ------------------------------------------------------
-- Server version	5.5.12-log

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
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `currency_iso_code` varchar(3) NOT NULL,
  `currency_name` varchar(100) NOT NULL,
  `sec_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currencies`
--

LOCK TABLES `currencies` WRITE;
/*!40000 ALTER TABLE `currencies` DISABLE KEYS */;
INSERT INTO `currencies` VALUES (1,'2011-12-01 13:01:02','UND','Undefined',0),(2,'2011-12-01 13:01:02','AUD','Australian Dollar',5),(3,'2011-12-01 13:01:02','BGL','Bulgarian Lev',12),(4,'2011-12-01 13:01:02','BRL','Brazilian Real',13),(5,'2011-12-01 13:01:02','CAD','Canadian Dollar',4),(6,'2011-12-01 13:01:02','CHF','Swiss Franc',7),(7,'2011-12-01 13:01:02','DKK','Danish Krona',14),(8,'2011-12-01 13:01:02','EUR','Euro',2),(9,'2011-12-01 13:01:02','GBP','Pound Sterling',3),(10,'2011-12-01 13:01:02','JPY','Japanese Yen',6),(11,'2011-12-01 13:01:02','KRW','Korean Won',11),(12,'2011-12-01 13:01:02','NOK','Norwegian Krona',8),(13,'2011-12-01 13:01:02','SEK','Swedish Krona',9),(14,'2011-12-01 13:01:02','USD','US Dollar',1),(15,'2011-12-01 13:06:07','ZWB','Zimbabwe Dollar',15),(16,'2012-02-08 11:00:24','MXN','Mexican Peso',16),(18,'2012-02-08 14:35:44','ILS','Israeli Shekel',17),(19,'2012-02-08 14:47:04','ZAR','South African Rand',18);
/*!40000 ALTER TABLE `currencies` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-10-23 16:53:05
