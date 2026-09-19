<?php
// Shared security helpers. Include after session_start().

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
