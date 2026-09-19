# GW Treasure Tracker

A small PHP/MySQL application for tracking free treasure drops in Guild Wars: Nightfall.

## Requirements
- PHP 7.0+ with mysqli
- MariaDB/MySQL
- A web server such as Apache

Copy `gw-connect-sample.php` to `gw-connect.php` and provide database credentials. Do not commit `gw-connect.php`.

## Database
`db/master-schema.sql` is a **destructive fresh-install schema**. It drops application tables before recreating them.

For an existing database created by the legacy schema, back it up and review/run `db/migrate-2026-hardening.sql` instead. The migration introduces `history.drop_type`, converts lookup columns from VARCHAR storage to numeric IDs, removes the old pseudo `itemtype` values 16/17, and adds referential-integrity constraints.

## Security hardening
The application uses prepared statements on user-controlled database operations, session regeneration at login, CSRF tokens for state-changing POSTs, character ownership validation, lookup validation, output escaping, and automatic upgrade of legacy MD5 password hashes after successful login.

Administrator access uses value `9` consistently. Broken legacy links to absent destructive admin handlers are intentionally disabled.
