<?php
session_start();
require_once 'gw-security.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['logout'])) {
    header('Location: gw-index.php');
    exit;
}
gw_require_csrf();

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'],
        (bool)$params['secure'], (bool)$params['httponly']);
}
session_destroy();
header('Location: gw-index.php');
exit;
