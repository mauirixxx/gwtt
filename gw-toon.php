<?php
session_start();
require_once 'gw-connect.php';
require_once 'gw-security.php';

if (empty($_SESSION['userid'])) {
    header('Location: gw-index.php');
    exit;
}

$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if ($con->connect_errno) { error_log('Database error: '.$con->connect_error); exit('A database error occurred.'); }
$con->set_charset('utf8mb4');

$userid = (int)$_SESSION['userid'];
$selectedToonId = 0;
$charactername = '';
$profcolor = '#ffffff';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    gw_require_csrf();
    $whattoon = isset($_POST['playerid']) ? (int)$_POST['playerid'] : 0;
    if ($whattoon > 0) {
        $stmt = $con->prepare('SELECT playerid, charname, profcolor FROM playername WHERE playerid = ? AND userid = ?');
        $stmt->bind_param('ii', $whattoon, $userid);
        $stmt->execute();
        if ($row = $stmt->get_result()->fetch_assoc()) {
            $selectedToonId = (int)$row['playerid'];
            $charactername = $row['charname'];
            $profcolor = preg_match('/^#[a-fA-F0-9]{6}$/', $row['profcolor']) ? $row['profcolor'] : '#ffffff';
            $_SESSION['playerid'] = $selectedToonId;
            $_SESSION['profcolor'] = $profcolor;
        } else {
            unset($_SESSION['playerid'], $_SESSION['profcolor']);
        }
        $stmt->close();
    } else {
        // A POST without playerid means "return to character selection".
        unset($_SESSION['playerid'], $_SESSION['profcolor']);
    }
} else {
    // A direct visit always starts at selection, preventing stale character context.
    unset($_SESSION['playerid'], $_SESSION['profcolor']);
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><link rel="stylesheet" type="text/css" href="gw-style.css">
<title><?php echo $selectedToonId ? htmlspecialchars($charactername, ENT_QUOTES, 'UTF-8') : 'Character Selection'; ?></title>
<style>body{background-color:<?php echo htmlspecialchars($profcolor, ENT_QUOTES, 'UTF-8'); ?>}</style></head><body>
<?php require 'gw-header.php'; ?>
<div style="text-align:center">
<?php if (!$selectedToonId): ?>
<form method="POST"><?php echo gw_csrf_input(); ?><select name="playerid" onchange="this.form.submit()"><option selected disabled>Select a Character</option>
<?php
$stmt=$con->prepare('SELECT playerid, charname FROM playername WHERE userid = ? ORDER BY charname ASC');
$stmt->bind_param('i',$userid); $stmt->execute(); $res=$stmt->get_result();
while($row=$res->fetch_assoc()) echo '<option value="'.(int)$row['playerid'].'">'.htmlspecialchars($row['charname'],ENT_QUOTES,'UTF-8').'</option>';
$stmt->close();
?></select><noscript><input type="submit" value="Choose Toon"></noscript></form>
<br><br><form action="gw-create.php" method="GET"><input type="submit" value="Add a toon"></form>
<?php else: ?>
<form method="POST" action="gw-action.php"><?php echo gw_csrf_input(); ?><fieldset class="radiogroup"><legend>Select your course of action</legend><ul class="radio" style="list-style:none;padding:0">
<li><label><input type="radio" name="gwaction" value="1" required> Record loot info</label></li>
<li><label><input type="radio" name="gwaction" value="2"> View Character loot history</label></li></ul></fieldset><br><input type="submit" value="Choose action"></form>
<br><br><form method="POST" action="gw-toon.php"><?php echo gw_csrf_input(); ?><input type="submit" value="Return to character selection"></form>
<?php endif; ?>
<br><br><form method="POST" action="gw-logout.php"><?php echo gw_csrf_input(); ?><input type="hidden" name="logout" value="1"><input type="submit" value="Logout"></form>
</div></body></html>
<?php $con->close(); ?>
