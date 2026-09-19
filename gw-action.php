<?php
session_start();
require_once 'gw-security.php';

if (empty($_SESSION['userid']) || empty($_SESSION['playerid'])) {
    header('Location: gw-toon.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: gw-toon.php');
    exit;
}
gw_require_csrf();

$action = (int)($_POST['gwaction'] ?? 0);
if ($action === 1) {
    header('Location: gw-location.php');
    exit;
}
if ($action === 2) {
    header('Location: gw-history.php');
    exit;
}
header('Location: gw-toon.php');
exit;
