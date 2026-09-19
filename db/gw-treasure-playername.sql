/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/* Table structure for table `playername` */

DROP TABLE IF EXISTS `playername`;

CREATE TABLE `playername` (
  `playerid`      INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `charname`      VARCHAR(30) NOT NULL,
  `birthdate`     DATE DEFAULT NULL,
  `userid`        INT(11) UNSIGNED NOT NULL,
  `professionid`  TINYINT(3) UNSIGNED NOT NULL COMMENT 'References listruneprofessions(runeprofid)',
  `profcolor`     VARCHAR(7) DEFAULT '#000000',
  
  -- Primary & Unique Constraints
  PRIMARY KEY (`playerid`),
  UNIQUE KEY `uk_charname` (`charname`),
  
  -- Performance Indexes
  INDEX `idx_user_player` (`userid`),
  INDEX `idx_profession` (`professionid`),

  -- Foreign Key Constraints
  CONSTRAINT `fk_player_user`
    FOREIGN KEY (`userid`) REFERENCES `users` (`userid`)
    ON DELETE CASCADE ON UPDATE CASCADE,

  CONSTRAINT `fk_player_profession`
    FOREIGN KEY (`professionid`) REFERENCES `listruneprofessions` (`runeprofid`)
    ON DELETE RESTRICT ON UPDATE CASCADE

) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
