/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/* Table structure for table `listrunes` */

DROP TABLE IF EXISTS `listrunes`;

CREATE TABLE `listrunes` (
  `runeid`        TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `runeclassid`   TINYINT(3) UNSIGNED NOT NULL,
  `runes`         VARCHAR(50) NOT NULL,
  
  -- Primary & Unique Constraints
  PRIMARY KEY (`runeid`),
  UNIQUE KEY `uk_rune_class` (`runeclassid`, `runes`),

  -- Foreign Key Constraint to listruneprofessions
  CONSTRAINT `fk_runes_profession`
    FOREIGN KEY (`runeclassid`) REFERENCES `listruneprofessions` (`runeprofid`)
    ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* Seed data for table `listrunes` */

INSERT INTO `listrunes` (`runeid`, `runeclassid`, `runes`) VALUES
(1, 1, 'Attunement'), (2, 1, 'Clarity'), (3, 1, 'Purity'), (4, 1, 'Recovery'), (5, 1, 'Restoration'), (6, 1, 'Vigor'), (7, 1, 'Vitae'),
(8, 2, 'Absorption'), (9, 2, 'Axe Mastery'), (10, 2, 'Hammer Mastery'), (11, 2, 'Strength'), (12, 2, 'Swordsmanship'), (13, 2, 'Tactics'),
(14, 3, 'Beast Mastery'), (15, 3, 'Expertise'), (16, 3, 'Marksmanship'), (17, 3, 'Wilderness Survival'),
(18, 4, 'Divine Favor'), (19, 4, 'Healing Prayers'), (20, 4, 'Protection Prayers'), (21, 4, 'Smiting Prayers'),
(22, 5, 'Blood Magic'), (23, 5, 'Curses'), (24, 5, 'Death Magic'), (25, 5, 'Soul Reaping'),
(26, 6, 'Domination Magic'), (27, 6, 'Fast Casting'), (28, 6, 'Illusion Magic'), (29, 6, 'Inspiration Magic'),
(30, 7, 'Air Magic'), (31, 7, 'Earth Magic'), (32, 7, 'Energy Storage'), (33, 7, 'Fire Magic'), (34, 7, 'Water Magic'),
(35, 8, 'Critical Strikes'), (36, 8, 'Dagger Mastery'), (37, 8, 'Deadly Arts'), (38, 8, 'Shadow Arts'),
(39, 9, 'Channeling Magic'), (40, 9, 'Communing'), (41, 9, 'Restoration Magic'), (42, 9, 'Spawning Power'),
(43, 10, 'Command'), (44, 10, 'Leadership'), (45, 10, 'Motivation'), (46, 10, 'Spear Mastery'),
(47, 11, 'Earth Prayers'), (48, 11, 'Mysticism'), (49, 11, 'Scythe Mastery'), (50, 11, 'Wind Prayers');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
