<TITLE>What Dropped?</TITLE>
<?php
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
#$toonid = mysqli_real_escape_string($con, $_POST['playerid']); //enable this after character selection is working
$toonid = 'Chrissi Chan';
if ($con->connect_errno > 0){
	die ('Unable to connect to database [' . $db->connect_errno . ']');
}

echo 'At |insert location SQL code here| a/an ';
//code for weapon attribute requirment
$sqlweapreq = "SELECT * FROM `listreq` ORDER BY `req` ASC";
if (!$result = $con->query($sqlweapreq)){
	die ('There was an error running the query [' . $con->error . ']');
}
echo '<SELECT NAME="requirement">';
while ($row = $result->fetch_array()){
	$reqid = $row['req'];
	echo '<OPTION VALUE="' . $reqid . '">' . $reqid . '</OPTION>';
}
echo '</SELECT>';

//code for white blue purple etc
$sqlweaprare = "SELECT * FROM `listrarity` ORDER BY `rareid` ASC";
if (!$result = $con->query($sqlweaprare)){
	die ('There was an error running the query [' . $con->error . ']');
}
echo '<SELECT NAME="rare">';
while ($row = $result->fetch_array()){
	$rareid = $row['rareid'];
	$rarity = $row['rarity'];
	echo '<OPTION VALUE="' . $rareid . '">' . $rarity . '</OPTION>';
}
echo '</SELECT>';

//code for what attribute the weapon is (command, axe mastery, energy storage, etc
$sqlweapattr = "SELECT * FROM `listattribute` ORDER BY `weapattrid` ASC";
if (!$result = $con->query($sqlweapattr)){
	die ('There was an error running the query [' . $con->error . ']');
}
echo '<SELECT NAME="attribute">';
while ($row = $result->fetch_array()){
	$attrid = $row['weapattrid'];
	$weapattr = $row['weaponattribute'];
	echo '<OPTION VALUE="' . $attrid . '">' . $weapattr . '</OPTION>';
}
echo '</SELECT>';
echo '<weapon/mat/rune code here>';
?>