/*
 Guild Wars Treasure Tracker - canonical fresh-install history table.
 Requires lookup/core tables to exist first. This is NOT an in-place migration.
*/
CREATE TABLE IF NOT EXISTS `history` (
  `historyid` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `historydate` DATE NOT NULL,
  `userid` INT UNSIGNED NOT NULL,
  `charnameid` INT UNSIGNED NOT NULL,
  `locationid` SMALLINT UNSIGNED NOT NULL,
  `goldrec` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
  `drop_type` TINYINT UNSIGNED NOT NULL COMMENT '1=Weapon, 2=Material, 3=Rune, 4=Nothing',
  `material` TINYINT UNSIGNED DEFAULT NULL,
  `itemreq` TINYINT UNSIGNED DEFAULT NULL,
  `itemtype` TINYINT UNSIGNED DEFAULT NULL,
  `itemattribute` TINYINT UNSIGNED DEFAULT NULL,
  `itemrarity` TINYINT UNSIGNED DEFAULT NULL,
  `itemname` VARCHAR(150) DEFAULT NULL,
  `runetype` TINYINT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`historyid`),
  INDEX `idx_user_char` (`userid`,`charnameid`),
  INDEX `idx_historydate` (`historydate`),
  INDEX `idx_location` (`locationid`),
  CONSTRAINT `fk_history_user` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_history_character` FOREIGN KEY (`charnameid`) REFERENCES `playername` (`playerid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_history_location` FOREIGN KEY (`locationid`) REFERENCES `treasuredata` (`treasureid`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_history_material` FOREIGN KEY (`material`) REFERENCES `materials` (`materialid`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_history_req` FOREIGN KEY (`itemreq`) REFERENCES `listreq` (`req`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_history_type` FOREIGN KEY (`itemtype`) REFERENCES `listtype` (`weaponid`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_history_attribute` FOREIGN KEY (`itemattribute`) REFERENCES `listattribute` (`weapattrid`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_history_rarity` FOREIGN KEY (`itemrarity`) REFERENCES `listrarity` (`rareid`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_history_rune` FOREIGN KEY (`runetype`) REFERENCES `listrunes` (`runeid`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `chk_history_drop_type` CHECK (`drop_type` BETWEEN 1 AND 4)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
