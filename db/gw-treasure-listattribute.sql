/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/* Table structure for table `listattribute` */

DROP TABLE IF EXISTS `listattribute`;

CREATE TABLE `listattribute` (
  `weapattrid`        TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `weaponattribute`   VARCHAR(50) NOT NULL COMMENT 'Death Magic, Axe Mastery, Command, etc.',
  
  -- Primary & Unique Constraints
  PRIMARY KEY (`weapattrid`),
  UNIQUE KEY `uk_weaponattribute` (`weaponattribute`)

) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* Seed data for table `listattribute` */

INSERT INTO `listattribute` (`weaponattribute`) VALUES
('Strength'), ('Swordsmanship'), ('Axe Mastery'), ('Hammer Mastery'), ('Tactics'),
('Expertise'), ('Marksmanship'), ('Wilderness Survival'), ('Beast Mastery'),
('Divine Favor'), ('Healing Prayers'), ('Protection Prayers'), ('Smiting Prayers'),
('Soul Reaping'), ('Blood Magic'), ('Curses'), ('Death Magic'),
('Fast Casting'), ('Inspiration Magic'), ('Domination Magic'), ('Illusion Magic'),
('Energy Storage'), ('Fire Magic'), ('Water Magic'), ('Air Magic'), ('Earth Magic'),
('Critical Strikes'), ('Dagger Mastery'), ('Deadly Arts'), ('Shadow Arts'),
('Spawning Power'), ('Channeling Magic'), ('Communing'), ('Restoration Magic'),
('Leadership'), ('Spear Mastery'), ('Command'), ('Motivation'),
('Mysticism'), ('Scythe Mastery'), ('Wind Prayers'), ('Earth Prayers');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
