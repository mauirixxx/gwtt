<TITLE>What Dropped?</TITLE>
<?php
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
#$toonid = mysqli_real_escape_string($con, $_POST['playerid']); //enable this after character selection is working
$toonid = 'Chrissi Chan';
#$location = mysqli_real_escape_string($con, $_POST['locationid']); //enable this after location selection is working
$location = 4;
if ($con->connect_errno > 0){
	die ('Unable to connect to database [' . $db->connect_errno . ']');
}

echo 'At ';
$sqlmaplocation = "SELECT `location`, `wikilink` FROM `treasurelocation` WHERE `treasureid` = $location";
if (!$result = $con->query($sqlmaplocation)){
	die ('There was an error running the query [' . $con->error . ']');
}
while ($row = $result->fetch_array()){
	$locname = $row['location'];
	$loclink = $row['wikilink'];
	echo '<A HREF="' . $loclink . '">' . $locname . '</A>';
}

echo ' a ';

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

//code for weapon attribute requirment
$sqlweapreq = "SELECT * FROM `listreq` ORDER BY `req` ASC";
if (!$result = $con->query($sqlweapreq)){
	die ('There was an error running the query [' . $con->error . ']');
}
echo 'r<SELECT NAME="requirement">';
while ($row = $result->fetch_array()){
	$reqid = $row['req'];
	echo '<OPTION VALUE="' . $reqid . '">' . $reqid . '</OPTION>';
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
	//need to add a nested while loop, to preselect the weapon with the attribute, or somehow java it? An r9 Axe of Energy Storage combo doesn't exist.
}
echo '</SELECT>';


echo ' |weapon/mat/rune code here|<BR /><BR />';
echo 'End result: a Gold r9 Swordsmanship Sword'; //need to arrange code blocks around to achieve this
?>