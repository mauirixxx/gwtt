/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `materials` */

DROP TABLE IF EXISTS `materials`;

CREATE TABLE `materials` (
  `materialid` int(2) NOT NULL AUTO_INCREMENT,
  `material` varchar(30) DEFAULT NULL COMMENT 'what kind of rare mat dropped: ecto, amber, silk, claw, ink, etc',
  PRIMARY KEY (`materialid`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

/*Data for the table `materials` */

insert  into `materials`(`materialid`,`material`) values (1,'No material drop'),(2,'Amber Chunk'),(3,'Bolt of Damask'),(4,'Bolt of Linen'),(5,'Bolt of Silk'),(6,'Deldrimor Steel Ingot'),(7,'Diamond'),(8,'Elonian Leather Square'),(9,'Fur Square'),(10,'Glob of Ectoplasm'),(11,'Jadeite Shard'),(12,'Leather Square'),(13,'Lump of Charcoal'),(14,'Monstrous Claw'),(15,'Monstrous Eye'),(16,'Monstrous Fang'),(17,'Obsidian Shard'),(18,'Onyx Gemstone'),(19,'Roll of Parchment'),(20,'Roll of Vellum'),(21,'Ruby'),(22,'Sapphire'),(23,'Spiritwood Plank'),(24,'Steel Ingot'),(25,'Tempered Glass Vial'),(26,'Vial of Ink');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
