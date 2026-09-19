/*
  Guild Wars Treasure Tracker - Master Database Schema
  Engine: InnoDB | Encoding: utf8mb4_unicode_ci
*/

/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- ==========================================
-- 1. DROP EXISTING TABLES (REVERSE DEPENDENCY ORDER)
-- ==========================================
DROP TABLE IF EXISTS `history`;
DROP TABLE IF EXISTS `playername`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `listrunes`;
DROP TABLE IF EXISTS `listruneprofessions`;
DROP TABLE IF EXISTS `listattribute`;
DROP TABLE IF EXISTS `listrarity`;
DROP TABLE IF EXISTS `listreq`;
DROP TABLE IF EXISTS `listtype`;
DROP TABLE IF EXISTS `materials`;
DROP TABLE IF EXISTS `treasuredata`;

-- ==========================================
-- 2. LOOKUP / REFERENCE TABLES
-- ==========================================

-- Table: listruneprofessions
CREATE TABLE `listruneprofessions` (
  `runeprofid`      TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `runeprofession`  VARCHAR(30) NOT NULL,
  PRIMARY KEY (`runeprofid`),
  UNIQUE KEY `uk_runeprofession` (`runeprofession`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `listruneprofessions` (`runeprofid`, `runeprofession`) VALUES
(1, 'None'), (2, 'Warrior'), (3, 'Ranger'), (4, 'Monk'), (5, 'Necromancer'),
(6, 'Mesmer'), (7, 'Elementalist'), (8, 'Assassin'), (9, 'Ritualist'), (10, 'Paragon'), (11, 'Dervish');

-- Table: listrunes
CREATE TABLE `listrunes` (
  `runeid`        TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `runeclassid`   TINYINT(3) UNSIGNED NOT NULL,
  `runes`         VARCHAR(50) NOT NULL,
  PRIMARY KEY (`runeid`),
  UNIQUE KEY `uk_rune_class` (`runeclassid`, `runes`),
  CONSTRAINT `fk_runes_profession`
    FOREIGN KEY (`runeclassid`) REFERENCES `listruneprofessions` (`runeprofid`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- Table: listattribute
CREATE TABLE `listattribute` (
  `weapattrid`        TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `weaponattribute`   VARCHAR(50) NOT NULL COMMENT 'Death Magic, Axe Mastery, Command, etc.',
  PRIMARY KEY (`weapattrid`),
  UNIQUE KEY `uk_weaponattribute` (`weaponattribute`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `listattribute` (`weaponattribute`) VALUES
('Strength'), ('Swordsmanship'), ('Axe Mastery'), ('Hammer Mastery'), ('Tactics'), ('Expertise'), ('Marksmanship'), ('Wilderness Survival'), ('Beast Mastery'), ('Divine Favor'), ('Healing Prayers'), ('Protection Prayers'), ('Smiting Prayers'), ('Soul Reaping'), ('Blood Magic'), ('Curses'), ('Death Magic'), ('Fast Casting'), ('Inspiration Magic'), ('Domination Magic'), ('Illusion Magic'), ('Energy Storage'), ('Fire Magic'), ('Water Magic'), ('Air Magic'), ('Earth Magic'), ('Critical Strikes'), ('Dagger Mastery'), ('Deadly Arts'), ('Shadow Arts'), ('Spawning Power'), ('Channeling Magic'), ('Communing'), ('Restoration Magic'), ('Leadership'), ('Spear Mastery'), ('Command'), ('Motivation'), ('Mysticism'), ('Scythe Mastery'), ('Wind Prayers'), ('Earth Prayers');

-- Table: listrarity
CREATE TABLE `listrarity` (
  `rareid`  TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rarity`  VARCHAR(20) NOT NULL COMMENT 'White, Blue, Purple, Gold, Green',
  PRIMARY KEY (`rareid`),
  UNIQUE KEY `uk_rarity` (`rarity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `listrarity` (`rarity`) VALUES
('White'), ('Blue'), ('Purple'), ('Gold'), ('Green');

-- Table: listreq
CREATE TABLE `listreq` (
  `req` TINYINT(3) UNSIGNED NOT NULL COMMENT 'Requirement level (0 through 13)',
  PRIMARY KEY (`req`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `listreq` (`req`) VALUES
(0), (1), (2), (3), (4), (5), (6), (7), (8), (9), (10), (11), (12), (13);

-- Table: listtype
CREATE TABLE `listtype` (
  `weaponid`    TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `weapontype`  VARCHAR(40) NOT NULL COMMENT 'Axe, Staff, Wand, Bow, Daggers, etc.',
  PRIMARY KEY (`weaponid`),
  UNIQUE KEY `uk_weapontype` (`weapontype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `listtype` (`weapontype`) VALUES
('Axe'), ('Hammer'), ('Sword'), ('Dagger'), ('Scythe'), ('Bow (Flatbow)'), ('Bow (Hornbow)'), ('Bow (Longbow)'), ('Bow (Recurve)'), ('Bow (Shortbow)'), ('Spear'), ('Staff'), ('Wand'), ('Focus'), ('Shield');

-- Table: materials
CREATE TABLE `materials` (
  `materialid`  TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `material`    VARCHAR(50) NOT NULL COMMENT 'Rare/Common crafting material drops',
  PRIMARY KEY (`materialid`),
  UNIQUE KEY `uk_material` (`material`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `materials` (`materialid`, `material`) VALUES
(1, 'No material drop'), (2, 'Amber Chunk'), (3, 'Bolt of Damask'), (4, 'Bolt of Linen'), (5, 'Bolt of Silk'), (6, 'Deldrimor Steel Ingot'), (7, 'Diamond'), (8, 'Elonian Leather Square'), (9, 'Fur Square'), (10, 'Glob of Ectoplasm'), (11, 'Jadeite Shard'), (12, 'Leather Square'), (13, 'Lump of Charcoal'), (14, 'Monstrous Claw'), (15, 'Monstrous Eye'), (16, 'Monstrous Fang'), (17, 'Obsidian Shard'), (18, 'Onyx Gemstone'), (19, 'Roll of Parchment'), (20, 'Roll of Vellum'), (21, 'Ruby'), (22, 'Sapphire'), (23, 'Spiritwood Plank'), (24, 'Steel Ingot'), (25, 'Tempered Glass Vial'), (26, 'Vial of Ink');

-- Table: treasuredata
CREATE TABLE `treasuredata` (
  `treasureid`  SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `location`    VARCHAR(100) NOT NULL,
  `wikilink`    VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`treasureid`),
  UNIQUE KEY `uk_location` (`location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- ==========================================
-- 3. CORE APPLICATION ENTITIES
-- ==========================================

-- Table: users
CREATE TABLE `users` (
  `userid`    INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`  VARCHAR(50) NOT NULL,
  `password`  VARCHAR(255) NOT NULL COMMENT 'Stores password_hash() outputs (Bcrypt/Argon2)',
  `email`     VARCHAR(255) NOT NULL,
  `access`    TINYINT(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=User, 1=Admin',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `uk_username` (`username`),
  UNIQUE KEY `uk_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: playername
CREATE TABLE `playername` (
  `playerid`      INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `charname`      VARCHAR(30) NOT NULL,
  `birthdate`     DATE DEFAULT NULL,
  `userid`        INT(11) UNSIGNED NOT NULL,
  `professionid`  TINYINT(3) UNSIGNED NOT NULL COMMENT 'References listruneprofessions(runeprofid)',
  `profcolor`     VARCHAR(7) DEFAULT '#000000',
  PRIMARY KEY (`playerid`),
  UNIQUE KEY `uk_charname` (`charname`),
  INDEX `idx_user_player` (`userid`),
  INDEX `idx_profession` (`professionid`),
  CONSTRAINT `fk_player_user`
    FOREIGN KEY (`userid`) REFERENCES `users` (`userid`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_player_profession`
    FOREIGN KEY (`professionid`) REFERENCES `listruneprofessions` (`runeprofid`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: history
CREATE TABLE `history` (
  `historyid`     INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `historydate`   DATE NOT NULL,
  `userid`        INT(11) UNSIGNED NOT NULL,
  `charnameid`    INT(11) UNSIGNED NOT NULL,
  `locationid`    SMALLINT(5) UNSIGNED DEFAULT NULL,
  `goldrec`       MEDIUMINT(8) UNSIGNED DEFAULT 0,
  `material`      VARCHAR(50) DEFAULT NULL,
  `itemreq`       TINYINT(3) UNSIGNED DEFAULT NULL,
  `itemtype`      VARCHAR(30) DEFAULT NULL,
  `itemattribute` VARCHAR(30) DEFAULT NULL,
  `itemrarity`    VARCHAR(20) DEFAULT NULL,
  `itemname`      VARCHAR(150) DEFAULT NULL,
  `runetype`      VARCHAR(50) DEFAULT NULL COMMENT 'Rune type (e.g., Clarity, Smiting Prayers, Axe Mastery)',
  PRIMARY KEY (`historyid`),
  INDEX `idx_user_char` (`userid`, `charnameid`),
  INDEX `idx_historydate` (`historydate`),
  INDEX `idx_location` (`locationid`),
  CONSTRAINT `fk_history_user` 
    FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) 
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_history_character` 
    FOREIGN KEY (`charnameid`) REFERENCES `playername` (`playerid`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
