<?php
session_start();
require_once 'gw-security.php';
if(empty($_SESSION['userid'])){header('Location: gw-index.php');exit;}
if((int)($_SESSION['access']??0)!==9){http_response_code(403);exit('Access denied.');}
$username=$_SESSION['username']??'Admin';
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><link rel="stylesheet" type="text/css" href="gw-style.css"><title>Admin Dashboard</title></head><body>
<div style="text-align:center;margin-top:50px"><h2>Administrator Tools</h2>
<p>The legacy delete-character and delete-user links were disabled because their target handlers are not present in this repository.</p>
<p>Destructive administration should be reintroduced only with explicit confirmation, CSRF protection, and transactional database handling.</p>
<p>Click <a href="gw-index.php" class="navlink">HERE</a> to return to the home page.</p><br><br>
<form method="POST" action="gw-logout.php"><?php echo gw_csrf_input(); ?><input type="hidden" name="logout" value="1"><input type="submit" value="Logout"></form></div></body></html>
