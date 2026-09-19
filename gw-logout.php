<?php
session_start();
require_once 'gw-security.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['logout'])) {
    header('Location: gw-index.php');
    exit;
}
gw_require_csrf();

gw_destroy_session();
header('Location: gw-index.php');
exit;
