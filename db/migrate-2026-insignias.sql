/* Add Guild Wars armor insignias to drop type 3 (Rune / Insignia). */
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS listinsignias (
  insigniaid TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  professionid TINYINT UNSIGNED NOT NULL,
  insignia VARCHAR(60) NOT NULL,
  PRIMARY KEY (insigniaid),
  UNIQUE KEY uk_insignia (insignia),
  KEY idx_insignia_profession (professionid),
  CONSTRAINT fk_insignia_profession FOREIGN KEY (professionid) REFERENCES listruneprofessions(runeprofid) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO listinsignias (professionid,insignia) VALUES
(1,'Survivor Insignia'),(1,'Radiant Insignia'),(1,'Stalwart Insignia'),(1,'Brawler\'s Insignia'),(1,'Blessed Insignia'),(1,'Herald\'s Insignia'),(1,'Sentry\'s Insignia'),
(2,'Knight\'s Insignia'),(2,'Stonefist Insignia'),(2,'Dreadnought Insignia'),(2,'Sentinel\'s Insignia'),(2,'Lieutenant\'s Insignia'),
(3,'Frostbound Insignia'),(3,'Pyrebound Insignia'),(3,'Stormbound Insignia'),(3,'Scout\'s Insignia'),(3,'Earthbound Insignia'),(3,'Beastmaster\'s Insignia'),
(4,'Wanderer\'s Insignia'),(4,'Disciple\'s Insignia'),(4,'Anchorite\'s Insignia'),
(5,'Bloodstained Insignia'),(5,'Tormentor\'s Insignia'),(5,'Bonelace Insignia'),(5,'Minion Master\'s Insignia'),(5,'Blighter\'s Insignia'),(5,'Undertaker\'s Insignia'),
(6,'Virtuoso\'s Insignia'),(6,'Artificer\'s Insignia'),(6,'Prodigy\'s Insignia'),
(7,'Hydromancer Insignia'),(7,'Geomancer Insignia'),(7,'Pyromancer Insignia'),(7,'Aeromancer Insignia'),(7,'Prismatic Insignia'),
(8,'Vanguard\'s Insignia'),(8,'Infiltrator\'s Insignia'),(8,'Saboteur\'s Insignia'),(8,'Nightstalker\'s Insignia'),
(9,'Shaman\'s Insignia'),(9,'Ghost Forge Insignia'),(9,'Mystic\'s Insignia'),
(10,'Centurion\'s Insignia'),
(11,'Windwalker Insignia'),(11,'Forsaken Insignia')
ON DUPLICATE KEY UPDATE professionid=VALUES(professionid);

SET @has_insignia := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='history' AND COLUMN_NAME='insignia'
);
SET @sql := IF(@has_insignia=0,
  'ALTER TABLE history ADD COLUMN insignia TINYINT UNSIGNED NULL AFTER runetype, ADD KEY idx_history_insignia (insignia), ADD CONSTRAINT fk_history_insignia FOREIGN KEY (insignia) REFERENCES listinsignias(insigniaid) ON DELETE RESTRICT ON UPDATE CASCADE',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
