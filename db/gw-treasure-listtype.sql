/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `listtype` */

DROP TABLE IF EXISTS `listtype`;

CREATE TABLE `listtype` (
  `weaponid` int(2) NOT NULL AUTO_INCREMENT,
  `weapontype` varchar(20) DEFAULT NULL COMMENT 'materials, or axe staff wand bow daggers etc'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `listtype` */

insert  into `listtype`(`weapontype`) values ('Axe'),('Hammer'),('Sword'),('Dagger'),('Scythe'),('Bow (Flatbow)'),('Bow (Hornbow)'),('Bow (Longbow)'),('Bow (Recurve)'),('Bow (Shortbow)'),('Spear'),('Staff'),('Wand'),('Focus'),('Shield');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
