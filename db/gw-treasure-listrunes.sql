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
/*Table structure for table `listrunes` */

CREATE TABLE `listrunes` (
  `runeid` int(2) NOT NULL AUTO_INCREMENT,
  `runeclassid` int(2) DEFAULT NULL,
  `runes` varchar(19) DEFAULT NULL,
  PRIMARY KEY (`runeid`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

/*Data for the table `listrunes` */

insert  into `listrunes`(`runeid`,`runeclassid`,`runes`) values (1,1,'Attunement'),(2,1,'Clarity'),(3,1,'Purity'),(4,1,'Recovery'),(5,1,'Restoration'),(6,1,'Vigor'),(7,1,'Vitae'),(8,2,'Absorption'),(9,2,'Axe Mastery'),(10,2,'Hammer Mastery'),(11,2,'Strength'),(12,2,'Swordsmanship'),(13,2,'Tactics'),(14,3,'Beast Mastery'),(15,3,'Expertise'),(16,3,'Marksmanship'),(17,3,'Wilderness Survival'),(18,4,'Divine Favor'),(19,4,'Healing Prayers'),(20,4,'Protection Prayer'),(21,4,'Smiting Prayers'),(22,5,'Blood Magic'),(23,5,'Curses'),(24,5,'Death Magic'),(25,5,'Soul Reaping'),(26,6,'Domination Magic'),(27,6,'Fast Casting'),(28,6,'Illusion Magic'),(29,6,'Inspiration Magic'),(30,7,'Air Magic'),(31,7,'Earth Magic'),(32,7,'Energy Storage'),(33,7,'Fire Magic'),(34,7,'Water Magic'),(35,8,'Critical Strikes'),(36,8,'Dagger Mastery'),(37,8,'Deadly Arts'),(38,8,'Shadow Arts'),(39,9,'Channeling Magic'),(40,9,'Communing'),(41,9,'Restoration Magic'),(42,9,'Spawning Power'),(43,10,'Command'),(44,10,'Leadership'),(45,10,'Motivation'),(46,10,'Spear Mastery'),(47,11,'Earth Prayers'),(48,11,'Mysticism'),(49,11,'Scythe Mastery'),(50,11,'Wind Prayers');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
