<?php
// Shared security helpers. Include after session_start().

const GW_SESSION_IDLE_TIMEOUT = 1800;      // 30 minutes
const GW_SESSION_ABSOLUTE_TIMEOUT = 28800; // 8 hours

function gw_destroy_session(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);
    }

    session_destroy();
}

function gw_enforce_session_timeout(): void
{
    // Timeout enforcement applies only to authenticated sessions.
    if (empty($_SESSION['userid'])) {
        return;
    }

    $now = time();

    // Gracefully initialize sessions that existed before timeout tracking
    // was deployed instead of immediately logging those users out.
    if (empty($_SESSION['authenticated_at'])) {
        $_SESSION['authenticated_at'] = $now;
    }
    if (empty($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = $now;
    }

    $idleExpired =
        ($now - (int)$_SESSION['last_activity']) >= GW_SESSION_IDLE_TIMEOUT;
    $absoluteExpired =
        ($now - (int)$_SESSION['authenticated_at']) >= GW_SESSION_ABSOLUTE_TIMEOUT;

    if ($idleExpired || $absoluteExpired) {
        gw_destroy_session();
        header('Location: gw-index.php?session=expired');
        exit;
    }

    $_SESSION['last_activity'] = $now;
}

gw_enforce_session_timeout();

function gw_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function gw_csrf_input(): string
{
    return '<input type="hidden" name="csrf_token" value="' .
        htmlspecialchars(gw_csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function gw_require_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || empty($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        exit('Invalid or expired request token.');
    }
}

const GW_AUTH_WINDOW_SECONDS = 900;       // 15 minutes
const GW_LOGIN_USER_LIMIT = 5;
const GW_LOGIN_IP_LIMIT = 20;
const GW_REGISTER_IP_LIMIT = 5;
const GW_AUTH_BLOCK_SECONDS = 900;        // 15 minutes

function gw_client_ip(): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : 'unknown';
}

function gw_throttle_key(string $scope, string $value): string
{
    return hash('sha256', $scope . ':' . strtolower($value));
}

function gw_throttle_is_blocked(mysqli $con, string $action, string $key): bool
{
    $stmt = $con->prepare(
        'SELECT blocked_until FROM auth_throttle
         WHERE throttle_key = ? AND action_type = ? LIMIT 1'
    );
    $stmt->bind_param('ss', $key, $action);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $row && $row['blocked_until'] !== null
        && strtotime($row['blocked_until']) > time();
}

function gw_throttle_record_failure(
    mysqli $con,
    string $action,
    string $key,
    int $limit
): void {
    // Read/update in a transaction so the threshold decision is based on
    // the post-increment count and concurrent attempts cannot lose updates.
    $con->begin_transaction();
    try {
        $stmt = $con->prepare(
            'SELECT failure_count, window_started_at
             FROM auth_throttle
             WHERE throttle_key = ? AND action_type = ?
             FOR UPDATE'
        );
        $stmt->bind_param('ss', $key, $action);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $now = time();
        if (!$row || strtotime($row['window_started_at']) <= ($now - GW_AUTH_WINDOW_SECONDS)) {
            $count = 1;
            $stmt = $con->prepare(
                'INSERT INTO auth_throttle
                    (throttle_key, action_type, failure_count, window_started_at, blocked_until, updated_at)
                 VALUES (?, ?, 1, NOW(), NULL, NOW())
                 ON DUPLICATE KEY UPDATE
                    failure_count = 1,
                    window_started_at = NOW(),
                    blocked_until = NULL,
                    updated_at = NOW()'
            );
            $stmt->bind_param('ss', $key, $action);
        } else {
            $count = (int)$row['failure_count'] + 1;
            if ($count >= $limit) {
                $stmt = $con->prepare(
                    'UPDATE auth_throttle
                     SET failure_count = ?, blocked_until = DATE_ADD(NOW(), INTERVAL ? SECOND), updated_at = NOW()
                     WHERE throttle_key = ? AND action_type = ?'
                );
                $block = GW_AUTH_BLOCK_SECONDS;
                $stmt->bind_param('iiss', $count, $block, $key, $action);
            } else {
                $stmt = $con->prepare(
                    'UPDATE auth_throttle
                     SET failure_count = ?, updated_at = NOW()
                     WHERE throttle_key = ? AND action_type = ?'
                );
                $stmt->bind_param('iss', $count, $key, $action);
            }
        }

        $stmt->execute();
        $stmt->close();
        $con->commit();
    } catch (Throwable $e) {
        $con->rollback();
        throw $e;
    }
}

function gw_throttle_clear(mysqli $con, string $action, string $key): void
{
    $stmt = $con->prepare(
        'DELETE FROM auth_throttle WHERE throttle_key = ? AND action_type = ?'
    );
    $stmt->bind_param('ss', $key, $action);
    $stmt->execute();
    $stmt->close();
}

function gw_throttle_cleanup(mysqli $con): void
{
    // Opportunistic cleanup; roughly 1% of authentication requests.
    if (random_int(1, 100) !== 1) {
        return;
    }

    $con->query(
        'DELETE FROM auth_throttle
         WHERE updated_at < DATE_SUB(NOW(), INTERVAL 2 DAY)'
    );
}

function gw_rate_limited_response(): void
{
    http_response_code(429);
    header('Retry-After: ' . GW_AUTH_BLOCK_SECONDS);
    exit(
        '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">' .
        '<title>Too Many Requests</title></head><body>' .
        '<div style="text-align:center;margin-top:50px">' .
        '<h2>Too Many Requests</h2>' .
        '<p>Too many attempts were received. Please wait 15 minutes and try again.</p>' .
        '<p><a href="gw-index.php">Return to sign in</a></p>' .
        '</div></body></html>'
    );
}

function gw_valid_date(string $date): bool
{
    $parts = explode('-', $date);
    return count($parts) === 3
        && ctype_digit($parts[0]) && ctype_digit($parts[1]) && ctype_digit($parts[2])
        && checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0]);
}

function gw_lookup_exists(mysqli $con, string $table, string $column, int $id): bool
{
    $allowed = [
        'treasuredata' => 'treasureid',
        'materials' => 'materialid',
        'listtype' => 'weaponid',
        'listattribute' => 'weapattrid',
        'listrarity' => 'rareid',
        'listrunes' => 'runeid',
        'listreq' => 'req',
        'listruneprofessions' => 'runeprofid',
    ];
    if (!isset($allowed[$table]) || $allowed[$table] !== $column) {
        return false;
    }
    $stmt = $con->prepare("SELECT 1 FROM `$table` WHERE `$column` = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $ok = (bool)$stmt->get_result()->fetch_row();
    $stmt->close();
    return $ok;
}

function gw_character_belongs_to_user(mysqli $con, int $playerId, int $userId): bool
{
    $stmt = $con->prepare('SELECT 1 FROM playername WHERE playerid = ? AND userid = ? LIMIT 1');
    $stmt->bind_param('ii', $playerId, $userId);
    $stmt->execute();
    $ok = (bool)$stmt->get_result()->fetch_row();
    $stmt->close();
    return $ok;
}


function gw_current_access(mysqli $con): int
{
    $userId = (int)($_SESSION['userid'] ?? 0);
    if ($userId <= 0) {
        return 0;
    }

    $stmt = $con->prepare('SELECT access FROM users WHERE userid = ? LIMIT 1');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $row ? (int)$row['access'] : 0;
}

function gw_require_admin(mysqli $con): void
{
    if (empty($_SESSION['userid'])) {
        header('Location: gw-index.php');
        exit;
    }

    if (gw_current_access($con) !== 9) {
        $_SESSION['access'] = 0;
        header('Location: gw-index.php');
        exit;
    }
}

function gw_refresh_session_access(mysqli $con): int
{
    $access = gw_current_access($con);
    $_SESSION['access'] = $access;
    return $access;
}
