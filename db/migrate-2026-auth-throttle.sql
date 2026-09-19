-- Add database-backed authentication throttling.
CREATE TABLE IF NOT EXISTS auth_throttle (
    throttle_key VARBINARY(191) NOT NULL,
    action_type VARCHAR(16) NOT NULL,
    failure_count INT UNSIGNED NOT NULL DEFAULT 0,
    window_started_at DATETIME NOT NULL,
    blocked_until DATETIME NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY (throttle_key, action_type),
    KEY idx_auth_throttle_updated (updated_at),
    KEY idx_auth_throttle_blocked (blocked_until)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
