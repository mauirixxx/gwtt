<?php
session_start();
require_once 'gw-connect.php';
require_once 'gw-security.php';

if (empty($_SESSION['userid']) || empty($_SESSION['playerid'])) {
    header('Location: gw-index.php');
    exit;
}
$con=new mysqli(DATABASE_HOST,DATABASE_USER,DATABASE_PASS,DATABASE_NAME);
if($con->connect_errno){error_log('Database connection failed: '.$con->connect_error);exit('A database error occurred.');}
$con->set_charset('utf8mb4');
$uid=(int)$_SESSION['userid']; $pid=(int)$_SESSION['playerid'];
if(!gw_character_belongs_to_user($con,$pid,$uid)){unset($_SESSION['playerid'],$_SESSION['profcolor']);$con->close();header('Location: gw-toon.php');exit;}
$profcolor=$_SESSION['profcolor']??'#ffffff';
if(!preg_match('/^#[a-fA-F0-9]{6}$/',$profcolor))$profcolor='#ffffff';
$result=$con->query('SELECT treasureid, location FROM treasuredata ORDER BY treasureid ASC');
if(!$result){error_log('Query failed: '.$con->error);exit('An error occurred while fetching locations.');}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><link rel="stylesheet" type="text/css" href="gw-style.css"><title>Location Selection</title>
<style>body{background-color:<?php echo htmlspecialchars($profcolor,ENT_QUOTES,'UTF-8'); ?>}</style></head><body><div style="text-align:center">
<form method="POST" action="gw-record.php"><?php echo gw_csrf_input(); ?><select name="locationid" onchange="this.form.submit()"><option selected disabled>Select a map location</option>
<?php while($row=$result->fetch_assoc()): ?><option value="<?php echo (int)$row['treasureid']; ?>"><?php echo htmlspecialchars($row['location'],ENT_QUOTES,'UTF-8'); ?></option><?php endwhile; ?>
</select><noscript><input type="submit" value="Choose Map Location"></noscript></form><br>
<form method="POST" action="gw-toon.php"><?php echo gw_csrf_input(); ?><input type="submit" value="Return to character selection"></form><br><br>
<form method="POST" action="gw-logout.php"><?php echo gw_csrf_input(); ?><input type="hidden" name="logout" value="1"><input type="submit" value="Logout"></form>
</div></body></html>
<?php $result->close();$con->close(); ?>
