/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/* Table structure for table `listruneprofessions` */

DROP TABLE IF EXISTS `listruneprofessions`;

CREATE TABLE `listruneprofessions` (
  `runeprofid`      TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `runeprofession`  VARCHAR(30) NOT NULL,
  
  -- Primary & Unique Constraints
  PRIMARY KEY (`runeprofid`),
  UNIQUE KEY `uk_runeprofession` (`runeprofession`)

) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* Seed data for table `listruneprofessions` */

INSERT INTO `listruneprofessions` (`runeprofid`, `runeprofession`) VALUES
(1, 'None'),
(2, 'Warrior'),
(3, 'Ranger'),
(4, 'Monk'),
(5, 'Necromancer'),
(6, 'Mesmer'),
(7, 'Elementalist'),
(8, 'Assassin'),
(9, 'Ritualist'),
(10, 'Paragon'),
(11, 'Dervish');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
