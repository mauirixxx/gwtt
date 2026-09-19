/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/* Table structure for table `listtype` */

DROP TABLE IF EXISTS `listtype`;

CREATE TABLE `listtype` (
  `weaponid`    TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `weapontype`  VARCHAR(40) NOT NULL COMMENT 'Axe, Staff, Wand, Bow, Daggers, etc.',
  
  -- Primary & Unique Constraints
  PRIMARY KEY (`weaponid`),
  UNIQUE KEY `uk_weapontype` (`weapontype`)

) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* Seed data for table `listtype` */

INSERT INTO `listtype` (`weapontype`) VALUES
('Axe'),
('Hammer'),
('Sword'),
('Dagger'),
('Scythe'),
('Bow (Flatbow)'),
('Bow (Hornbow)'),
('Bow (Longbow)'),
('Bow (Recurve)'),
('Bow (Shortbow)'),
('Spear'),
('Staff'),
('Wand'),
('Focus'),
('Shield');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
