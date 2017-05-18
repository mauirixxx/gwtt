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
/*Table structure for table `listruneprofessions` */

CREATE TABLE `listruneprofessions` (
  `runeprofid` int(2) NOT NULL AUTO_INCREMENT,
  `runeprofession` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`runeprofid`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `listruneprofessions` */

insert  into `listruneprofessions`(`runeprofid`,`runeprofession`) values (1,'None'),(2,'Warrior'),(3,'Ranger'),(4,'Monk'),(5,'Necromancer'),(6,'Mesmer'),(7,'Elementalist'),(8,'Assassin'),(9,'Ritualist'),(10,'Paragon'),(11,'Dervish');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
