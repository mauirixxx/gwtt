/*
SQLyog Ultimate - MySQL GUI v8.2 
MySQL - 5.5.52-MariaDB : Database - mauirixxx
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `playername` */

CREATE TABLE `playername` (
  `playerid` int(11) NOT NULL AUTO_INCREMENT,
  `charname` varchar(19) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `professionid` int(2) DEFAULT NULL COMMENT 'this is taken from the listruneprofessions table',
  `profcolor` char(4) DEFAULT NULL,
  PRIMARY KEY (`playerid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
