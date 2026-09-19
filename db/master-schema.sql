/*
 Guild Wars Treasure Tracker - MASTER FRESH-INSTALL SCHEMA
 WARNING: DESTRUCTIVE. This file drops application tables. Do not run it against
 a populated production database. Use db/migrate-2026-hardening.sql for upgrades.
*/
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS history; DROP TABLE IF EXISTS playername; DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS listrunes; DROP TABLE IF EXISTS listruneprofessions; DROP TABLE IF EXISTS listattribute;
DROP TABLE IF EXISTS listrarity; DROP TABLE IF EXISTS listreq; DROP TABLE IF EXISTS listtype;
DROP TABLE IF EXISTS materials; DROP TABLE IF EXISTS treasuredata;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE listruneprofessions (runeprofid TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,runeprofession VARCHAR(30) NOT NULL,PRIMARY KEY(runeprofid),UNIQUE KEY uk_runeprofession(runeprofession)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO listruneprofessions VALUES (1,'None'),(2,'Warrior'),(3,'Ranger'),(4,'Monk'),(5,'Necromancer'),(6,'Mesmer'),(7,'Elementalist'),(8,'Assassin'),(9,'Ritualist'),(10,'Paragon'),(11,'Dervish');

CREATE TABLE listrunes (runeid TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,runeclassid TINYINT UNSIGNED NOT NULL,runes VARCHAR(50) NOT NULL,PRIMARY KEY(runeid),UNIQUE KEY uk_rune_class(runeclassid,runes),CONSTRAINT fk_runes_profession FOREIGN KEY(runeclassid) REFERENCES listruneprofessions(runeprofid) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO listrunes VALUES
(1,1,'Attunement'),(2,1,'Clarity'),(3,1,'Purity'),(4,1,'Recovery'),(5,1,'Restoration'),(6,1,'Vigor'),(7,1,'Vitae'),
(8,2,'Absorption'),(9,2,'Axe Mastery'),(10,2,'Hammer Mastery'),(11,2,'Strength'),(12,2,'Swordsmanship'),(13,2,'Tactics'),
(14,3,'Beast Mastery'),(15,3,'Expertise'),(16,3,'Marksmanship'),(17,3,'Wilderness Survival'),(18,4,'Divine Favor'),(19,4,'Healing Prayers'),(20,4,'Protection Prayers'),(21,4,'Smiting Prayers'),
(22,5,'Blood Magic'),(23,5,'Curses'),(24,5,'Death Magic'),(25,5,'Soul Reaping'),(26,6,'Domination Magic'),(27,6,'Fast Casting'),(28,6,'Illusion Magic'),(29,6,'Inspiration Magic'),
(30,7,'Air Magic'),(31,7,'Earth Magic'),(32,7,'Energy Storage'),(33,7,'Fire Magic'),(34,7,'Water Magic'),(35,8,'Critical Strikes'),(36,8,'Dagger Mastery'),(37,8,'Deadly Arts'),(38,8,'Shadow Arts'),
(39,9,'Channeling Magic'),(40,9,'Communing'),(41,9,'Restoration Magic'),(42,9,'Spawning Power'),(43,10,'Command'),(44,10,'Leadership'),(45,10,'Motivation'),(46,10,'Spear Mastery'),
(47,11,'Earth Prayers'),(48,11,'Mysticism'),(49,11,'Scythe Mastery'),(50,11,'Wind Prayers');

CREATE TABLE listattribute (weapattrid TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,weaponattribute VARCHAR(50) NOT NULL,PRIMARY KEY(weapattrid),UNIQUE KEY uk_weaponattribute(weaponattribute)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO listattribute(weaponattribute) VALUES ('Strength'),('Swordsmanship'),('Axe Mastery'),('Hammer Mastery'),('Tactics'),('Expertise'),('Marksmanship'),('Wilderness Survival'),('Beast Mastery'),('Divine Favor'),('Healing Prayers'),('Protection Prayers'),('Smiting Prayers'),('Soul Reaping'),('Blood Magic'),('Curses'),('Death Magic'),('Fast Casting'),('Inspiration Magic'),('Domination Magic'),('Illusion Magic'),('Energy Storage'),('Fire Magic'),('Water Magic'),('Air Magic'),('Earth Magic'),('Critical Strikes'),('Dagger Mastery'),('Deadly Arts'),('Shadow Arts'),('Spawning Power'),('Channeling Magic'),('Communing'),('Restoration Magic'),('Leadership'),('Spear Mastery'),('Command'),('Motivation'),('Mysticism'),('Scythe Mastery'),('Wind Prayers'),('Earth Prayers');

CREATE TABLE listrarity (rareid TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,rarity VARCHAR(20) NOT NULL,PRIMARY KEY(rareid),UNIQUE KEY uk_rarity(rarity)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO listrarity(rarity) VALUES ('White'),('Blue'),('Purple'),('Gold'),('Green');
CREATE TABLE listreq (req TINYINT UNSIGNED NOT NULL,PRIMARY KEY(req)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO listreq VALUES (0),(1),(2),(3),(4),(5),(6),(7),(8),(9),(10),(11),(12),(13);
CREATE TABLE listtype (weaponid TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,weapontype VARCHAR(40) NOT NULL,PRIMARY KEY(weaponid),UNIQUE KEY uk_weapontype(weapontype)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO listtype(weapontype) VALUES ('Axe'),('Hammer'),('Sword'),('Dagger'),('Scythe'),('Bow (Flatbow)'),('Bow (Hornbow)'),('Bow (Longbow)'),('Bow (Recurve)'),('Bow (Shortbow)'),('Spear'),('Staff'),('Wand'),('Focus'),('Shield');
CREATE TABLE materials (materialid TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,material VARCHAR(50) NOT NULL,PRIMARY KEY(materialid),UNIQUE KEY uk_material(material)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO materials(materialid,material) VALUES (1,'No material drop'),(2,'Amber Chunk'),(3,'Bolt of Damask'),(4,'Bolt of Linen'),(5,'Bolt of Silk'),(6,'Deldrimor Steel Ingot'),(7,'Diamond'),(8,'Elonian Leather Square'),(9,'Fur Square'),(10,'Glob of Ectoplasm'),(11,'Jadeite Shard'),(12,'Leather Square'),(13,'Lump of Charcoal'),(14,'Monstrous Claw'),(15,'Monstrous Eye'),(16,'Monstrous Fang'),(17,'Obsidian Shard'),(18,'Onyx Gemstone'),(19,'Roll of Parchment'),(20,'Roll of Vellum'),(21,'Ruby'),(22,'Sapphire'),(23,'Spiritwood Plank'),(24,'Steel Ingot'),(25,'Tempered Glass Vial'),(26,'Vial of Ink');
CREATE TABLE treasuredata (treasureid SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,location VARCHAR(100) NOT NULL,wikilink VARCHAR(255),PRIMARY KEY(treasureid),UNIQUE KEY uk_location(location)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO treasuredata VALUES (1,'Issnur Isles','https://wiki.guildwars.com/wiki/Issnur_Isles'),(2,'Mehtani Keys','https://wiki.guildwars.com/wiki/Mehtani_Keys'),(3,'Arkjok Ward','https://wiki.guildwars.com/wiki/Arkjok_Ward'),(4,'Jahai Bluffs','https://wiki.guildwars.com/wiki/Jahai_Bluffs'),(5,'Bahdok Caverns','https://wiki.guildwars.com/wiki/Bahdok_Caverns'),(6,'The Mirror of Lyss','https://wiki.guildwars.com/wiki/The_Mirror_of_Lyss'),(7,'The Hidden City of Ahdashim','https://wiki.guildwars.com/wiki/The_Hidden_City_of_Ahdashim'),(8,'Forum Highlands','https://wiki.guildwars.com/wiki/Forum_Highlands'),(9,'The Sulfurous Wastes','https://wiki.guildwars.com/wiki/The_Sulfurous_Wastes'),(10,'The Ruptured Heart','https://wiki.guildwars.com/wiki/The_Ruptured_Heart'),(11,'Nightfallen Jahai','https://wiki.guildwars.com/wiki/Nightfallen_Jahai'),(12,'Domain of Pain','https://wiki.guildwars.com/wiki/Domain_of_Pain');

CREATE TABLE users (userid INT UNSIGNED NOT NULL AUTO_INCREMENT,username VARCHAR(50) NOT NULL,password VARCHAR(255) NOT NULL,email VARCHAR(255) NOT NULL,access TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=User, 9=Admin',PRIMARY KEY(userid),UNIQUE KEY uk_username(username),UNIQUE KEY uk_email(email)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE playername (playerid INT UNSIGNED NOT NULL AUTO_INCREMENT,charname VARCHAR(30) NOT NULL,birthdate DATE DEFAULT NULL,userid INT UNSIGNED NOT NULL,professionid TINYINT UNSIGNED NOT NULL,profcolor VARCHAR(7) DEFAULT '#000000',PRIMARY KEY(playerid),UNIQUE KEY uk_charname(charname),KEY idx_user_player(userid),CONSTRAINT fk_player_user FOREIGN KEY(userid) REFERENCES users(userid) ON DELETE CASCADE ON UPDATE CASCADE,CONSTRAINT fk_player_profession FOREIGN KEY(professionid) REFERENCES listruneprofessions(runeprofid) ON DELETE RESTRICT ON UPDATE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*
 Guild Wars Treasure Tracker - canonical fresh-install history table.
 Requires lookup/core tables to exist first. This is NOT an in-place migration.
*/
CREATE TABLE `history` (
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
