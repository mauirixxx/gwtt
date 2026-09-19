/* GWTT - valid weapon/item type to requirement-attribute mappings.
   Random/ordinary loot rules. Anniversary-only attribute exceptions are intentionally excluded.
   Safe for production: creates/refreshes lookup data only; does not alter history rows. */
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS weapon_attribute_map (
  weaponid TINYINT UNSIGNED NOT NULL,
  weapattrid TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (weaponid,weapattrid),
  KEY idx_weapon_attribute_attr (weapattrid),
  CONSTRAINT fk_weapon_attribute_type FOREIGN KEY (weaponid) REFERENCES listtype(weaponid) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_weapon_attribute_attribute FOREIGN KEY (weapattrid) REFERENCES listattribute(weapattrid) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM weapon_attribute_map;

INSERT INTO weapon_attribute_map (weaponid,weapattrid)
SELECT t.weaponid,a.weapattrid
FROM listtype t
JOIN listattribute a ON
 (t.weapontype='Axe' AND a.weaponattribute='Axe Mastery') OR
 (t.weapontype='Hammer' AND a.weaponattribute='Hammer Mastery') OR
 (t.weapontype='Sword' AND a.weaponattribute='Swordsmanship') OR
 (t.weapontype='Dagger' AND a.weaponattribute='Dagger Mastery') OR
 (t.weapontype='Scythe' AND a.weaponattribute='Scythe Mastery') OR
 (t.weapontype LIKE 'Bow (%' AND a.weaponattribute='Marksmanship') OR
 (t.weapontype='Spear' AND a.weaponattribute='Spear Mastery') OR
 (t.weapontype IN ('Staff','Wand','Focus') AND a.weaponattribute IN (
   'Divine Favor','Healing Prayers','Protection Prayers','Smiting Prayers',
   'Soul Reaping','Blood Magic','Curses','Death Magic',
   'Fast Casting','Inspiration Magic','Domination Magic','Illusion Magic',
   'Energy Storage','Fire Magic','Water Magic','Air Magic','Earth Magic',
   'Spawning Power','Channeling Magic','Communing','Restoration Magic'
 )) OR
 (t.weapontype='Shield' AND a.weaponattribute IN ('Strength','Tactics','Leadership','Command','Motivation'));
