-- MySQL dump 10.13  Distrib 8.0.33, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: lno
-- ------------------------------------------------------
-- Server version	8.0.33

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
-- Table structure for table `event_comment`
--

DROP TABLE IF EXISTS `event_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `event_id` int NOT NULL,
  `created_by` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `edited_by` int DEFAULT NULL,
  `edited_at` datetime DEFAULT NULL,
  `comment` varchar(800) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `event_comment_user_id_fk` (`created_by`),
  KEY `event_comment_user_id_fk2` (`edited_by`),
  KEY `event_comment_event_id_fk` (`event_id`),
  CONSTRAINT `event_comment_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `event_comment_user_id_fk` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  CONSTRAINT `event_comment_user_id_fk2` FOREIGN KEY (`edited_by`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Comments on a specific event';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_comment`
--

/*!40000 ALTER TABLE `event_comment` DISABLE KEYS */;
INSERT INTO `event_comment` VALUES (48,23136,45,'2023-06-20 19:43:04',NULL,NULL,NULL,'I\'m going to this event'),(49,23136,49,'2023-06-20 19:43:04',NULL,NULL,NULL,'I\'m going to this event'),(50,23136,28,'2023-06-20 19:43:04',NULL,NULL,NULL,'I\'m going to this event'),(51,23136,21,'2023-06-20 19:43:04',NULL,NULL,NULL,'I\'m going to this event'),(52,23136,100,'2023-06-20 19:43:04',NULL,NULL,NULL,'I\'m going to this event'),(53,23136,6,'2023-06-20 19:43:04',NULL,NULL,NULL,'I\'m going to this event'),(54,23136,72,'2023-06-20 19:43:04',NULL,NULL,NULL,'I\'m going to this event');
/*!40000 ALTER TABLE `event_comment` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-06-20 22:04:43
