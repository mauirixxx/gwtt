/*
 GWTT migration: enforce unique usernames and email addresses.

 Run the duplicate checks first. The ALTER TABLE statements will fail safely
 if duplicate values already exist.
*/

-- These should both return zero rows before applying the constraints.
SELECT username, COUNT(*) AS duplicate_count
FROM users
GROUP BY username
HAVING COUNT(*) > 1;

SELECT email, COUNT(*) AS duplicate_count
FROM users
GROUP BY email
HAVING COUNT(*) > 1;

ALTER TABLE users
  ADD UNIQUE KEY uk_username (username),
  ADD UNIQUE KEY uk_email (email);
