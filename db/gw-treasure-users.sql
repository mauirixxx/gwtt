/* Guild Wars Treasure Tracker - canonical fresh-install users table. */
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `userid` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL COMMENT 'password_hash() output; legacy MD5 is upgraded on successful login',
  `email` VARCHAR(255) NOT NULL,
  `access` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=User, 9=Admin',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `uk_username` (`username`),
  UNIQUE KEY `uk_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
