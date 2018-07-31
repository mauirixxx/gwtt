/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `listattribute` */

DROP TABLE IF EXISTS `listattribute`;

CREATE TABLE `listattribute` (
  `weapattrid` int(2) NOT NULL AUTO_INCREMENT,
  `weaponattribute` varchar(20) DEFAULT NULL COMMENT 'death magic, axe mastery, command, smiting prayers, etc'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `listattribute` */

insert  into `listattribute`(`weaponattribute`) values ('Strength'),('Swordsmanship'),('Axe Mastery'),('Hammer Mastery'),('Tactics'),('Expertise'),('Marksmanship'),('Wilderness Survival'),('Beast Mastery'),('Divine Favor'),('Healing Prayers'),('Protection Prayers'),('Smiting Prayers'),('Soul Reaping'),('Blood Magic'),('Curses'),('Death Magic'),('Fast Casting'),('Inspiration Magic'),('Domination Magic'),('Illusion Magic'),('Energy Storage'),('Fire Magic'),('Water Magic'),('Air Magic'),('Earth Magic'),('Critical Strikes'),('Dagger Mastery'),('Deadly Arts'),('Shadow Arts'),('Spawning Power'),('Channeling Magic'),('Communing'),('Restoration Magic'),('Leadership'),('Spear Mastery'),('Command'),('Motivation'),('Mysticism'),('Scythe Mastery'),('Wind Prayers'),('Earth Prayers');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
