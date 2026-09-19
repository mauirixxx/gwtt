<?php
session_start();
require_once 'gw-connect.php';
require_once 'gw-security.php';
if(empty($_SESSION['userid'])||empty($_SESSION['playerid'])){header('Location: gw-index.php');exit;}
$con=new mysqli(DATABASE_HOST,DATABASE_USER,DATABASE_PASS,DATABASE_NAME);
if($con->connect_errno){error_log('Database error: '.$con->connect_error);exit('Database connection failed.');}
$con->set_charset('utf8mb4');
$uid=(int)$_SESSION['userid'];$toonid=(int)$_SESSION['playerid'];
if(!gw_character_belongs_to_user($con,$toonid,$uid)){unset($_SESSION['playerid'],$_SESSION['profcolor']);$con->close();header('Location: gw-toon.php');exit;}
if($_SERVER['REQUEST_METHOD']==='POST')gw_require_csrf();
$location=isset($_POST['locationid'])?(int)$_POST['locationid']:0;$whatdropped=isset($_POST['gwdrop'])?(int)$_POST['gwdrop']:0;
$profcolor=$_SESSION['profcolor']??'#ffffff';if(!preg_match('/^#(?:[a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/',$profcolor))$profcolor='#ffffff';
$locname='';$loclink='';$locid=0;
if($location>0){$stmt=$con->prepare('SELECT treasureid,location,wikilink FROM treasuredata WHERE treasureid=?');$stmt->bind_param('i',$location);$stmt->execute();if($row=$stmt->get_result()->fetch_assoc()){$locid=(int)$row['treasureid'];$locname=$row['location'];$loclink=$row['wikilink'];}$stmt->close();}
function options(mysqli $c,string $sql,string $id,string $label):void{$r=$c->query($sql);while($x=$r->fetch_assoc())echo '<option value="'.(int)$x[$id].'">'.htmlspecialchars((string)$x[$label],ENT_QUOTES,'UTF-8').'</option>';$r->close();}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><link rel="stylesheet" type="text/css" href="gw-style.css"><title>What Dropped?</title><style>body{background-color:<?php echo htmlspecialchars($profcolor,ENT_QUOTES,'UTF-8'); ?>}</style></head><body>
<?php require 'gw-header.php'; ?>
<div style="text-align:center"><?php if($locid): ?>At <a href="<?php echo htmlspecialchars($loclink,ENT_QUOTES,'UTF-8'); ?>" class="navlink"><?php echo htmlspecialchars($locname,ENT_QUOTES,'UTF-8'); ?></a> (Guild Wars Wiki link)<?php else: ?><p>Invalid location selected.</p><?php endif; ?></div><br>
<?php if($locid && $whatdropped===1): ?><div style="text-align:center"><form method="POST" action="gw-insert.php"><?php echo gw_csrf_input(); ?>
on <input name="treasuredate" type="date" value="<?php echo date('Y-m-d'); ?>" required> a
<select name="rare"><?php options($con,'SELECT rareid,rarity FROM listrarity ORDER BY rareid','rareid','rarity'); ?></select>,
req<select name="requirement"><?php options($con,'SELECT req FROM listreq ORDER BY req','req','req'); ?></select>
<select name="attribute"><?php options($con,'SELECT weapattrid,weaponattribute FROM listattribute ORDER BY weapattrid','weapattrid','weaponattribute'); ?></select>
<select name="weapon"><?php options($con,'SELECT weaponid,weapontype FROM listtype ORDER BY weaponid','weaponid','weapontype'); ?></select>
called the <input type="text" name="itemname" maxlength="150" size="40"> and <input type="number" name="droppedgold" min="0" max="9999" value="0" required> gold pieces.
<input type="hidden" name="droptype" value="1"><input type="hidden" name="location" value="<?php echo $locid; ?>"><br><input type="submit" value="Submit Drop"></form></div>
<?php elseif($locid && $whatdropped===2): ?><div style="text-align:center"><form method="POST" action="gw-insert.php"><?php echo gw_csrf_input(); ?>
on <input name="treasuredate" type="date" value="<?php echo date('Y-m-d'); ?>" required> a <select name="rarematerial"><?php options($con,'SELECT materialid,material FROM materials ORDER BY materialid','materialid','material'); ?></select>
and <input type="number" name="droppedgold" min="0" max="9999" value="0" required> gold pieces.<input type="hidden" name="droptype" value="2"><input type="hidden" name="location" value="<?php echo $locid; ?>"><br><input type="submit" value="Submit Drop"></form></div>
<?php elseif($locid && $whatdropped===3): ?><div style="text-align:center"><form method="POST" action="gw-insert.php"><?php echo gw_csrf_input(); ?>
on <input name="treasuredate" type="date" value="<?php echo date('Y-m-d'); ?>" required> a <select name="runerarity"><option value="2">Blue</option><option value="3">Purple</option><option value="4">Gold</option></select> rune of
<select name="rune"><?php options($con,'SELECT runeid,runes FROM listrunes ORDER BY runeid','runeid','runes'); ?></select> and <input type="number" name="droppedgold" min="0" max="9999" value="0" required> gold pieces.
<input type="hidden" name="droptype" value="3"><input type="hidden" name="location" value="<?php echo $locid; ?>"><br><input type="submit" value="Submit Drop"></form></div>
<?php elseif($locid && $whatdropped===4): ?><div style="text-align:center"><form method="POST" action="gw-insert.php"><?php echo gw_csrf_input(); ?>
on <input name="treasuredate" type="date" value="<?php echo date('Y-m-d'); ?>" required> nothing dropped!<input type="hidden" name="droppedgold" value="0"><input type="hidden" name="droptype" value="4"><input type="hidden" name="location" value="<?php echo $locid; ?>"><br><input type="submit" value="Submit Drop"></form></div>
<?php elseif($locid): ?><div style="text-align:center"><form method="POST"><?php echo gw_csrf_input(); ?><select name="gwdrop" onchange="this.form.submit()"><option selected disabled>Choose drop type</option><option value="1">Weapon</option><option value="2">Rare Material</option><option value="3">Rune</option><option value="4">Nothing!</option></select><input type="hidden" name="locationid" value="<?php echo $locid; ?>"><noscript><input type="submit" value="Submit"></noscript></form></div><?php endif; ?>
<br><div style="text-align:center"><a href="gw-location.php" class="navlink">Return to location selection</a><br><br>
<form method="POST" action="gw-logout.php"><?php echo gw_csrf_input(); ?><input type="hidden" name="logout" value="1"><input type="submit" value="Logout"></form></div></body></html>
<?php $con->close(); ?>
