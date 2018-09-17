-- MySQL dump 10.13  Distrib 5.7.21, for Linux (x86_64)
--
-- Host: localhost    Database: msa_cms_dev
-- ------------------------------------------------------
-- Server version	5.7.21-0ubuntu0.17.10.1

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
-- Table structure for table `action_text`
--

DROP TABLE IF EXISTS `action_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `action_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `external_id` varchar(99) NOT NULL COMMENT 'action_text external ID',
  `original_url` varchar(255) DEFAULT NULL,
  `statetrack_url` varchar(255) DEFAULT NULL,
  `type` varchar(99) DEFAULT NULL,
  `regulation_id` int(11) NOT NULL COMMENT 'FK to regulations.id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `external_id` (`external_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hearing`
--

DROP TABLE IF EXISTS `hearing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hearing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `legislation_external_id` varchar(99) NOT NULL,
  `external_id` varchar(50) NOT NULL COMMENT 'fabricated md5 of date, time, house, committee',
  `date` date NOT NULL,
  `time` varchar(50) NOT NULL,
  `house` varchar(50) DEFAULT NULL,
  `committee` varchar(250) DEFAULT NULL,
  `place` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leg_date_time` (`house`,`legislation_external_id`,`date`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `legislation`
--

DROP TABLE IF EXISTS `legislation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `legislation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `external_id` varchar(99) NOT NULL,
  `session` varchar(150) NOT NULL,
  `state` varchar(150) NOT NULL,
  `type` varchar(150) NOT NULL,
  `number` varchar(250) NOT NULL,
  `title` text NOT NULL,
  `abstract` text NOT NULL,
  `full_text_url` text NOT NULL,
  `sponsor_name` varchar(250) NOT NULL,
  `sponsor_url` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `external_id` (`external_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `profile_keyword`
--

DROP TABLE IF EXISTS `profile_keyword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile_keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_match_id` int(11) NOT NULL,
  `keyword` varchar(99) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `match_keyword` (`profile_match_id`,`keyword`)
) ENGINE=InnoDB AUTO_INCREMENT=242 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `profile_match`
--

DROP TABLE IF EXISTS `profile_match`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile_match` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `external_id` varchar(99) NOT NULL COMMENT 'profile external ID',
  `pname` varchar(99) NOT NULL COMMENT 'the parsed xml profile name',
  `entity_type` varchar(16) NOT NULL COMMENT 'matched entity type: legislation, regulation, hearing',
  `entity_id` int(11) NOT NULL COMMENT 'ID of the matched entity',
  `client` varchar(99) NOT NULL COMMENT 'client name',
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_pname_bill` (`client`,`external_id`,`entity_type`,`entity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `regulation`
--

DROP TABLE IF EXISTS `regulation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `regulation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `external_id` varchar(25) NOT NULL,
  `tracking_key` varchar(100) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `agency_name` varchar(250) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `state_action_type` varchar(100) DEFAULT NULL,
  `full_text_id` int(11) DEFAULT NULL,
  `full_text_url` varchar(999) DEFAULT NULL,
  `full_text_local_url` varchar(999) DEFAULT NULL,
  `full_text_type` text,
  `code_citation` varchar(250) DEFAULT NULL,
  `description` text,
  `register_date` varchar(100) DEFAULT NULL,
  `register_citation` varchar(100) DEFAULT NULL,
  `register_url` varchar(999) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `external_id` (`external_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-03-05 19:15:24
