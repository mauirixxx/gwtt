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
  `runes` varchar(17) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `listrunes` */

insert  into `listrunes`(`runes`) values ('Attunement'),('Clarity'),('Purity'),('Recovery'),('Restoration'),('Vigor'),('Vitae'),('Absorption'),('Axe Mastery'),('Hammer Mastery'),('Strength'),('Swordsmanship'),('Tactics'),('Beast Mastery'),('Expertise'),('Marksmanship'),('Wilderness Surviv'),('Divine Favor'),('Healing Prayers'),('Protection Prayer'),('Smiting Prayers'),('Blood Magic'),('Curses'),('Death Magic'),('Soul Reaping'),('Domination Magic'),('Fast Casting'),('Illusion Magic'),('Inspiration Magic'),('Air Magic'),('Earth Magic'),('Energy Storage'),('Fire Magic'),('Water Magic'),('Critical Strikes'),('Dagger Mastery'),('Deadly Arts'),('Shadow Arts'),('Channeling Magic'),('Communing'),('Restoration Magic'),('Spawning Power'),('Command'),('Leadership'),('Motivation'),('Spear Mastery'),('Earth Prayers'),('Mysticism'),('Scythe Mastery'),('Wind Prayers');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
