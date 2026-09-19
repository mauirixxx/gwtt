/*
 GWTT 2026 hardening migration - IN-PLACE UPGRADE
 BACK UP THE DATABASE BEFORE RUNNING THIS FILE.

 Assumption verified from the application: legacy history rows stored numeric lookup
 IDs in VARCHAR columns. Legacy itemtype 16 means Rune; 17 means Nothing.
*/
START TRANSACTION;

ALTER TABLE history
  ADD COLUMN drop_type TINYINT UNSIGNED NULL AFTER goldrec;

UPDATE history
SET drop_type = CASE
  WHEN CAST(itemtype AS UNSIGNED) = 16 THEN 3
  WHEN CAST(itemtype AS UNSIGNED) = 17 THEN 4
  WHEN material IS NOT NULL AND material <> '' AND CAST(material AS UNSIGNED) > 0 THEN 2
  ELSE 1
END
WHERE drop_type IS NULL;

-- Remove legacy pseudo weapon IDs before adding the real listtype FK.
UPDATE history SET itemtype = NULL WHERE CAST(itemtype AS UNSIGNED) IN (16,17);

ALTER TABLE history
  MODIFY drop_type TINYINT UNSIGNED NOT NULL,
  MODIFY material TINYINT UNSIGNED NULL,
  MODIFY itemtype TINYINT UNSIGNED NULL,
  MODIFY itemattribute TINYINT UNSIGNED NULL,
  MODIFY itemrarity TINYINT UNSIGNED NULL,
  MODIFY runetype TINYINT UNSIGNED NULL,
  MODIFY locationid SMALLINT UNSIGNED NOT NULL,
  MODIFY goldrec MEDIUMINT UNSIGNED NOT NULL DEFAULT 0;

ALTER TABLE history
  ADD CONSTRAINT fk_history_location FOREIGN KEY(locationid) REFERENCES treasuredata(treasureid) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT fk_history_material FOREIGN KEY(material) REFERENCES materials(materialid) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT fk_history_req FOREIGN KEY(itemreq) REFERENCES listreq(req) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT fk_history_type FOREIGN KEY(itemtype) REFERENCES listtype(weaponid) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT fk_history_attribute FOREIGN KEY(itemattribute) REFERENCES listattribute(weapattrid) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT fk_history_rarity FOREIGN KEY(itemrarity) REFERENCES listrarity(rareid) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT fk_history_rune FOREIGN KEY(runetype) REFERENCES listrunes(runeid) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT chk_history_drop_type CHECK(drop_type BETWEEN 1 AND 4);

COMMIT;
