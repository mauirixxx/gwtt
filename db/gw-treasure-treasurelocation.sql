/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/* Table structure for table `treasuredata` */

DROP TABLE IF EXISTS `treasuredata`;

CREATE TABLE `treasuredata` (
  `treasureid`  SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `location`    VARCHAR(100) NOT NULL,
  `wikilink`    VARCHAR(255) DEFAULT NULL,
  
  -- Primary & Unique Constraints
  PRIMARY KEY (`treasureid`),
  UNIQUE KEY `uk_location` (`location`)

) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* Seed data for table `treasuredata` */

INSERT INTO `treasuredata` (`treasureid`, `location`, `wikilink`) VALUES
(1, 'Issnur Isles', 'https://wiki.guildwars.com/wiki/Issnur_Isles'),
(2, 'Mehtani Keys', 'https://wiki.guildwars.com/wiki/Mehtani_Keys'),
(3, 'Arkjok Ward', 'https://wiki.guildwars.com/wiki/Arkjok_Ward'),
(4, 'Jahai Bluffs', 'https://wiki.guildwars.com/wiki/Jahai_Bluffs'),
(5, 'Bahdok Caverns', 'https://wiki.guildwars.com/wiki/Bahdok_Caverns'),
(6, 'The Mirror of Lyss', 'https://wiki.guildwars.com/wiki/The_Mirror_of_Lyss'),
(7, 'The Hidden City of Ahdashim', 'https://wiki.guildwars.com/wiki/The_Hidden_City_of_Ahdashim'),
(8, 'Forum Highlands', 'https://wiki.guildwars.com/wiki/Forum_Highlands'),
(9, 'The Sulfurous Wastes', 'https://wiki.guildwars.com/wiki/The_Sulfurous_Wastes'),
(10, 'The Ruptured Heart', 'https://wiki.guildwars.com/wiki/The_Ruptured_Heart'),
(11, 'Nightfallen Jahai', 'https://wiki.guildwars.com/wiki/Nightfallen_Jahai'),
(12, 'Domain of Pain', 'https://wiki.guildwars.com/wiki/Domain_of_Pain');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
