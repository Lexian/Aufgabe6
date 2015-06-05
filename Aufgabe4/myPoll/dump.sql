-- MySQL dump 10.13  Distrib 5.5.42, for osx10.6 (i386)
--
-- Host: localhost    Database: myPoller
-- ------------------------------------------------------
-- Server version	5.5.42

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
-- Table structure for table `pollAnswers`
--

DROP TABLE IF EXISTS `pollAnswers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pollAnswers` (
  `answerID` int(11) NOT NULL AUTO_INCREMENT,
  `ansText` varchar(100) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `pollID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`answerID`),
  KEY `pollID` (`pollID`),
  CONSTRAINT `polloptions_ibfk_1` FOREIGN KEY (`pollID`) REFERENCES `poll_form` (`pollID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pollAnswers`
--

LOCK TABLES `pollAnswers` WRITE;
/*!40000 ALTER TABLE `pollAnswers` DISABLE KEYS */;
/*!40000 ALTER TABLE `pollAnswers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `poll_form`
--

DROP TABLE IF EXISTS `poll_form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_form` (
  `pollID` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `title` varchar(100) NOT NULL,
  `description` longtext,
  PRIMARY KEY (`pollID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poll_form`
--

LOCK TABLES `poll_form` WRITE;
/*!40000 ALTER TABLE `poll_form` DISABLE KEYS */;
/*!40000 ALTER TABLE `poll_form` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votedetails`
--

DROP TABLE IF EXISTS `votedetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `votedetails` (
  `voteID` int(11) NOT NULL DEFAULT '0',
  `answerID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`voteID`,`answerID`),
  KEY `answerID` (`answerID`),
  CONSTRAINT `votedetails_ibfk_13` FOREIGN KEY (`voteID`) REFERENCES `votes` (`voteID`) ON DELETE CASCADE,
  CONSTRAINT `votedetails_ibfk_14` FOREIGN KEY (`answerID`) REFERENCES `pollAnswers` (`answerID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votedetails`
--

LOCK TABLES `votedetails` WRITE;
/*!40000 ALTER TABLE `votedetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `votedetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `votes` (
  `voteID` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pollID` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(40) DEFAULT NULL,
  `cookie` int(11) DEFAULT NULL,
  `userknown` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`voteID`),
  KEY `pollID` (`pollID`),
  CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`pollID`) REFERENCES `poll_form` (`pollID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6781 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `votes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-06-02  2:19:27
