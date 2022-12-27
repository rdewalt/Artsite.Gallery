-- MySQL dump 10.13  Distrib 5.7.13, for osx10.11 (x86_64)
--
-- Host: localhost    Database:updere
-- ------------------------------------------------------
-- Server version	5.7.13

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
-- Table structure for table `ArtFolders`
--

DROP TABLE IF EXISTS `ArtFolders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ArtFolders` (
  `FolderID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` int(10) unsigned DEFAULT NULL,
  `FolderName` varchar(32) DEFAULT NULL,
  `ParentFolder` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`FolderID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `Serials`
--

DROP TABLE IF EXISTS `Serials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Serials` (
  `SerialID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ArtistID` int(10) unsigned DEFAULT NULL,
  `SerialName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`SerialID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `UserIcon`
--

DROP TABLE IF EXISTS `UserIcon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UserIcon` (
  `UserID` int(10) unsigned DEFAULT NULL,
  `imageid` int(10) unsigned DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `Latest` datetime DEFAULT NULL,
  UNIQUE KEY `UserID_UNIQUE` (`UserID`),
  KEY `idx_UserIcon_UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UserIcon`
--

LOCK TABLES `UserIcon` WRITE;
/*!40000 ALTER TABLE `UserIcon` DISABLE KEYS */;
INSERT INTO `UserIcon` VALUES (1,143,'rdewalt','2016-07-10 00:48:02');
/*!40000 ALTER TABLE `UserIcon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `Cat_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Cat_Name` varchar(32) DEFAULT NULL,
  `NSFW` char(1) NOT NULL DEFAULT 'N',
  `DefautState` char(1) DEFAULT 'Y',
  `ParentCategory` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Cat_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (2,'Abstract','N','Y',2),(3,'Adoptables','N','Y',2),(4,'Animation','N','Y',2),(5,'Anime','N','Y',2),(6,'Artwork (Digital)','N','Y',2),(7,'Artwork (Traditional)','N','Y',2),(8,'Auctions','N','Y',2),(9,'Cartoon','N','Y',2),(10,'Comic','N','Y',2),(11,'Craft','N','Y',2),(12,'Fanart','N','Y',2),(13,'Fantasy','N','Y',2),(14,'Fursuit','N','Y',2),(15,'Human','N','Y',2),(16,'Photography','N','Y',2),(17,'Plush','N','Y',2),(18,'Portraits','N','Y',2),(19,'Scenery','N','Y',2),(20,'Scrap','N','Y',2),(21,'Screenshots','N','Y',2),(22,'Sculpting','N','Y',2),(23,'Still Life','N','Y',2),(24,'Stock Art','N','Y',2),(25,'Tutorials','N','Y',2),(26,'Wallpaper','N','Y',2),(27,'YCH','N','Y',2),(28,'Short Story','N','Y',2),(29,'Story Chapter','N','Y',2),(30,'Novel','N','Y',2),(31,'Poetry','N','Y',2),(32,'Music','N','Y',2),(33,'Podcasts','N','Y',2),(34,'Other Audio','N','Y',2),(35,'Uncategorized','Y','N',2),(36,'My User Icon','N','N',2),(37,'My User Banner','N','N',2);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category_groups`
--

DROP TABLE IF EXISTS `category_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Cat_Name` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category_groups`
--

LOCK TABLES `category_groups` WRITE;
/*!40000 ALTER TABLE `category_groups` DISABLE KEYS */;
INSERT INTO `category_groups` VALUES (1,'Visual Art'),(2,'Written Art'),(3,'Audio Art'),(4,'Adult Art'),(5,'Uncategorised');
/*!40000 ALTER TABLE `category_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `CommentID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `WhichID` int(10) unsigned DEFAULT NULL,
  `WhichType` char(1) DEFAULT NULL,
  `WhenSaid` datetime DEFAULT NULL,
  `WhoSaid` int(10) unsigned DEFAULT NULL,
  `WhatSaid` varchar(10000) DEFAULT NULL,
  PRIMARY KEY (`CommentID`),
  KEY `idx_comments_WhichID` (`WhichID`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `image_faves`
--

DROP TABLE IF EXISTS `image_faves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image_faves` (
  `UserID` int(10) unsigned DEFAULT NULL,
  `ImageID` int(10) unsigned DEFAULT NULL,
  UNIQUE KEY `idx_image_faves_UserID_ImageID` (`UserID`,`ImageID`),
  KEY `idx_image_faves_UserID` (`UserID`),
  KEY `idx_image_faves_ImageID` (`ImageID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `image_views`
--

DROP TABLE IF EXISTS `image_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image_views` (
  `ImageID` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `ImageID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` int(10) unsigned DEFAULT NULL,
  `ShortID` varchar(255) DEFAULT NULL,
  `UploadDate` datetime DEFAULT NULL,
  `shard` char(2) DEFAULT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `Filename` varchar(255) DEFAULT NULL,
  `Medium` varchar(255) DEFAULT NULL,
  `Thumbnail` varchar(255) DEFAULT NULL,
  `Category` int(10) unsigned DEFAULT NULL,
  `NSFW` char(1) DEFAULT NULL,
  `ViewCount` int(10) unsigned DEFAULT '0',
  `FaveCount` int(10) unsigned DEFAULT '0',
  `State` char(1) DEFAULT NULL,
  `SerialID` int(10) unsigned DEFAULT NULL,
  `SerialPosition` int(10) unsigned DEFAULT NULL,
  `FolderID` int(10) unsigned DEFAULT NULL,
  `OriginalFilename` varchar(255) DEFAULT NULL,
  `height` int(10) unsigned DEFAULT NULL,
  `width` int(10) unsigned DEFAULT NULL,
  `Keywords` text,
  `Description` text,
  PRIMARY KEY (`ImageID`),
  FULLTEXT KEY `Title` (`Title`,`Keywords`,`Description`)
) ENGINE=MyISAM AUTO_INCREMENT=158 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `userbans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userbans` (
  `userid` int(10) unsigned DEFAULT NULL,
  `banid` int(10) unsigned DEFAULT NULL,
  KEY `idx_userbans_userid` (`userid`),
  KEY `idx_userbans_banid` (`banid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `role` char(1) NOT NULL DEFAULT 'U',
  `password` char(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-07-28  2:27:54
