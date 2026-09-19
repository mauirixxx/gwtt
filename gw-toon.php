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
$profcolor = '#DDD';
$lastPlayerId = isset($_SESSION['last_playerid']) ? (int)$_SESSION['last_playerid'] : 0;
$dropConfirmation = null;
$confirmationHistoryId = isset($_SESSION['drop_confirmation_historyid']) ? (int)$_SESSION['drop_confirmation_historyid'] : 0;
unset($_SESSION['drop_confirmation_historyid']);
if ($confirmationHistoryId > 0) {
    $stmt = $con->prepare("SELECT h.historydate,h.goldrec,h.drop_type,h.itemreq,h.itemname,p.charname,t.weapontype,a.weaponattribute,r.rarity,m.material,ru.runes,td.location FROM history h JOIN playername p ON p.playerid=h.charnameid AND p.userid=h.userid JOIN treasuredata td ON td.treasureid=h.locationid LEFT JOIN listtype t ON t.weaponid=h.itemtype LEFT JOIN listattribute a ON a.weapattrid=h.itemattribute LEFT JOIN listrarity r ON r.rareid=h.itemrarity LEFT JOIN materials m ON m.materialid=h.material LEFT JOIN listrunes ru ON ru.runeid=h.runetype WHERE h.historyid=? AND h.userid=? LIMIT 1");
    $stmt->bind_param('ii',$confirmationHistoryId,$userid);$stmt->execute();$dropConfirmation=$stmt->get_result()->fetch_assoc();$stmt->close();
}

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
            $profcolor = preg_match('/^#(?:[a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/', $row['profcolor']) ? $row['profcolor'] : '#ffffff';
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
<?php if ($dropConfirmation): ?>
<?php
$dropText='';
switch((int)$dropConfirmation['drop_type']){
case 1:$dropText=trim(($dropConfirmation['rarity']??'').' req '.($dropConfirmation['itemreq']??'').' '.($dropConfirmation['weaponattribute']??'').' '.($dropConfirmation['weapontype']??'').(!empty($dropConfirmation['itemname'])?' called "'.$dropConfirmation['itemname'].'"':''));break;
case 2:$dropText=(string)($dropConfirmation['material']??'Rare material');break;
case 3:$dropText=trim(($dropConfirmation['rarity']??'').' '.($dropConfirmation['runes']??'').' rune');break;
default:$dropText='Nothing dropped';break;
}
?>
<p><strong>Drop recorded!</strong> <?php echo htmlspecialchars($dropConfirmation['charname'],ENT_QUOTES,'UTF-8'); ?> recorded <?php echo htmlspecialchars($dropText,ENT_QUOTES,'UTF-8'); ?> at <?php echo htmlspecialchars($dropConfirmation['location'],ENT_QUOTES,'UTF-8'); ?> on <?php echo htmlspecialchars($dropConfirmation['historydate'],ENT_QUOTES,'UTF-8'); ?><?php if((int)$dropConfirmation['goldrec']>0): ?>, plus <?php echo number_format((int)$dropConfirmation['goldrec']); ?> gold<?php endif; ?>.</p>
<?php endif; ?>
<?php if (!$selectedToonId): ?>
<form method="POST"><?php echo gw_csrf_input(); ?><select name="playerid" onchange="this.form.submit()"><option disabled<?php echo $lastPlayerId ? '' : ' selected'; ?>>Select a Character</option>
<?php
$stmt=$con->prepare('SELECT playerid, charname FROM playername WHERE userid = ? ORDER BY charname ASC');
$stmt->bind_param('i',$userid); $stmt->execute(); $res=$stmt->get_result();
while($row=$res->fetch_assoc()){ $pid=(int)$row['playerid']; echo '<option value="'.$pid.'">'.htmlspecialchars($row['charname'],ENT_QUOTES,'UTF-8').'</option>'; }
$stmt->close();
?></select><?php if($lastPlayerId): ?><button type="submit" name="playerid" value="<?php echo $lastPlayerId; ?>">Use last character</button><?php endif; ?><noscript><?php if(!$lastPlayerId): ?><input type="submit" value="Choose Toon"><?php endif; ?></noscript></form>
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
