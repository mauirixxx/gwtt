<TITLE>What Dropped?</TITLE>
<?php
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
#$toonid = mysqli_real_escape_string($con, $_POST['playerid']); //enable this after character selection is working
$toonid = 'Chrissi Chan';
if ($con->connect_errno > 0){
	die ('Unable to connect to database [' . $db->connect_errno . ']');
}
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
echo '</SELECT><BR />';

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
echo '</SELECT><BR />';
?>