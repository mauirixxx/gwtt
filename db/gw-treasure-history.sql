/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.5.52-MariaDB : Database - mauirixxx
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `history` */

CREATE TABLE `history` (
  `historydate` date DEFAULT NULL,
  `charnameid` int(11) DEFAULT NULL,
  `locationid` int(2) DEFAULT NULL,
  `goldrec` int(4) DEFAULT NULL,
  `material` varchar(30) DEFAULT NULL,
  `itemreq` int(2) DEFAULT NULL,
  `itemtype` varchar(13) DEFAULT NULL,
  `itemattribute` varchar(15) DEFAULT NULL,
  `itemrarity` varchar(6) DEFAULT NULL,
  `itemname` varchar(100) DEFAULT NULL,
  `runetype` varchar(25) DEFAULT NULL COMMENT 'what type of rune is it (clarity, smiting prayers, axe mastery, etc'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
