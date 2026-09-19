/*
  Hardened Schema for Table: history
*/

-- 1. Ensure Table Creation Uses Modern Engine and Unicode Charset
CREATE TABLE IF NOT EXISTS `history` (
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
  
  -- Primary Key
  PRIMARY KEY (`historyid`),
  
  -- Indexes for Query Optimization
  INDEX `idx_user_char` (`userid`, `charnameid`),
  INDEX `idx_historydate` (`historydate`),
  INDEX `idx_location` (`locationid`),

  -- Foreign Key Constraints (Enforces Referential Integrity)
  CONSTRAINT `fk_history_user` 
    FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) 
    ON DELETE CASCADE ON UPDATE CASCADE,
    
  CONSTRAINT `fk_history_character` 
    FOREIGN KEY (`charnameid`) REFERENCES `playername` (`playerid`) 
    ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
