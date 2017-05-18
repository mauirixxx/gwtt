/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `treasuredata` */

DROP TABLE IF EXISTS `treasuredata`;

CREATE TABLE `treasuredata` (
  `treasureid` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(27) DEFAULT NULL,
  `wikilink` varchar(59) DEFAULT NULL,
  PRIMARY KEY (`treasureid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `treasuredata` */

insert  into `treasuredata`(`treasureid`,`location`,`wikilink`) values (1,'Issnur Isles','https://wiki.guildwars.com/wiki/Issnur_Isles'),(2,'Mehtani Keys','https://wiki.guildwars.com/wiki/Mehtani_Keys'),(3,'Arkjok Ward','https://wiki.guildwars.com/wiki/Arkjok_Ward'),(4,'Jahai Bluffs','https://wiki.guildwars.com/wiki/Jahai_Bluffs'),(5,'Bahdok Caverns','https://wiki.guildwars.com/wiki/Bahdok_Caverns'),(6,'The Mirror of Lyss','https://wiki.guildwars.com/wiki/The_Mirror_of_Lyss'),(7,'The Hidden City of Ahdashim','https://wiki.guildwars.com/wiki/The_Hidden_City_of_Ahdashim'),(8,'Forum Highlands','https://wiki.guildwars.com/wiki/Forum_Highlands'),(9,'The Sulfurous Wastes','https://wiki.guildwars.com/wiki/The_Sulfurous_Wastes'),(10,'The Ruptured Heart','https://wiki.guildwars.com/wiki/The_Ruptured_Heart'),(11,'Nightfallen Jahai','https://wiki.guildwars.com/wiki/Nightfallen_Jahai'),(12,'Domain of Pain','https://wiki.guildwars.com/wiki/Domain_of_Pain');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
