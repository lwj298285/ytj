-- MySQL dump 10.13  Distrib 5.7.14, for Win64 (x86_64)
--
-- Host: localhost    Database: homestead
-- ------------------------------------------------------
-- Server version	5.7.14

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
-- Table structure for table `wf_admin`
--

DROP TABLE IF EXISTS `wf_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wf_admin` (
  `id` int(11) NOT NULL,
  `jobnum` varchar(25) NOT NULL,
  `username` varchar(50) NOT NULL,
  `sex` tinyint(4) NOT NULL DEFAULT '1',
  `password` varchar(255) DEFAULT '',
  `mobile` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `loginnum` int(11) DEFAULT '0',
  `last_login_ip` varchar(30) DEFAULT NULL,
  `last_login_time` int(11) DEFAULT '0',
  `real_name` varchar(255) NOT NULL,
  `status` int(11) DEFAULT '1',
  `job` varchar(20) DEFAULT NULL,
  `jobtitle` varchar(20) DEFAULT NULL,
  `usertype` tinyint(4) DEFAULT '0',
  `_token` varchar(255) DEFAULT NULL,
  `groupid` int(11) NOT NULL,
  `isdel` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`jobnum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wf_auth_group`
--

DROP TABLE IF EXISTS `wf_auth_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wf_auth_group` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `rules` varchar(200) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `isdel` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wf_auth_group_access`
--

DROP TABLE IF EXISTS `wf_auth_group_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wf_auth_group_access` (
  `uid` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`uid`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wf_auth_rule`
--

DROP TABLE IF EXISTS `wf_auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wf_auth_rule` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `title` varchar(20) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `css` varchar(50) DEFAULT NULL,
  `condition` varchar(100) DEFAULT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `isdel` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wf_log`
--

DROP TABLE IF EXISTS `wf_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wf_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志记录id',
  `admin_id` int(11) DEFAULT NULL COMMENT '用户id',
  `admin_name` varchar(50) DEFAULT NULL COMMENT '用户名',
  `description` varchar(300) DEFAULT NULL COMMENT '描述',
  `ip` char(60) DEFAULT NULL COMMENT 'ip地址',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态',
  `add_time` int(11) DEFAULT NULL COMMENT '操作时间',
  `isdel` tinyint(4) NOT NULL DEFAULT '1' COMMENT '默认1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=378 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-09 14:52:08
