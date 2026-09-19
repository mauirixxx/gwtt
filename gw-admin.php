<?php
session_start();
require_once 'gw-connect.php';
require_once 'gw-security.php';

$con=new mysqli(DATABASE_HOST,DATABASE_USER,DATABASE_PASS,DATABASE_NAME);
if($con->connect_errno){error_log('Database error: '.$con->connect_error);exit('A database error occurred.');}
$con->set_charset('utf8mb4');
gw_require_admin($con);
gw_refresh_session_access($con);
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><link rel="stylesheet" href="gw-style.css"><title>Administrator Tools</title>
<style>.admin-wrap{max-width:760px;margin:35px auto;padding:0 20px}.admin-card{background:rgba(255,255,255,.78);border:1px solid #bbb;border-radius:6px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,.08)}.admin-card h2{text-align:center;margin:0 0 26px}.admin-menu{display:grid;grid-template-columns:1fr 1fr;gap:18px}.admin-menu a{background:#fff;border:1px solid #aaa;border-radius:5px;color:#003f73;display:block;font-size:1.1rem;font-weight:700;padding:22px;text-align:center;text-decoration:none}.admin-menu a:hover,.admin-menu a:focus{background:#f2f2f2}@media(max-width:600px){.admin-menu{grid-template-columns:1fr}}</style></head><body>
<?php require 'gw-header.php'; ?>
<main class="admin-wrap"><section class="admin-card"><h2>Administrator Tools</h2><nav class="admin-menu" aria-label="Administrator tools"><a href="gw-admin-users.php">User Management</a><a href="gw-admin-characters.php">Character / Loot Management</a></nav></section></main>
</body></html>
<?php $con->close(); ?>
