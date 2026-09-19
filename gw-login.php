<?php
session_start();
require_once 'gw-connect.php';
require_once 'gw-security.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: gw-index.php');
    exit;
}
gw_require_csrf();

$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if ($con->connect_errno) {
    error_log('Database connection failed: ' . $con->connect_error);
    exit('A database error occurred. Please try again later.');
}
$con->set_charset('utf8mb4');

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$loginSuccess = false;

if ($username !== '' && $password !== '' && strlen($username) <= 50) {
    $stmt = $con->prepare('SELECT userid, username, password, access FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row) {
        $stored = (string)$row['password'];
        $legacyMd5 = preg_match('/^[a-f0-9]{32}$/i', $stored) === 1
            && hash_equals(strtolower($stored), md5($password));
        if (password_verify($password, $stored) || $legacyMd5) {
            if ($legacyMd5 || password_needs_rehash($stored, PASSWORD_DEFAULT)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $update = $con->prepare('UPDATE users SET password = ? WHERE userid = ?');
                $uid = (int)$row['userid'];
                $update->bind_param('si', $newHash, $uid);
                $update->execute();
                $update->close();
            }
            session_regenerate_id(true);
            $_SESSION['username'] = $row['username'];
            $_SESSION['userid'] = (int)$row['userid'];
            $_SESSION['access'] = (int)$row['access'];
            unset($_SESSION['playerid'], $_SESSION['profcolor']);
            $loginSuccess = true;
        }
    }
}
$con->close();

if ($loginSuccess) {
    header('Location: gw-index.php');
    exit;
}
http_response_code(401);
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><link rel="stylesheet" type="text/css" href="gw-style.css"><title>Invalid Login</title></head>
<body><div style="text-align:center;margin-top:50px"><h2>Invalid Login</h2><p>That was not a valid username or password.</p><p>Please try again <a href="gw-index.php" class="navlink">here</a>.</p></div></body></html>
