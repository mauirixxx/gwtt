<?php
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if ($con->connect_errno > 0){
	die ('Unable to connect to database [' . $db->connect_errno . ']');
}
$sql = "SELECT playerid, charname FROM `playername` WHERE `userid` = '1' ORDER BY `charname` ASC"; //need to make userid a variable
if (!$result = $con->query($sql)){
	die ('There was an error running the query [' . $con->error . ']');
}
echo '<FORM METHOD="POST" NAME="cselect" ACTION="gw-pull.php">';
echo '<SELECT NAME="cname">';
while ($row = $result->fetch_array()){
	$charid = $row['playerid'];
	$charname = $row['charname'];
	echo '<OPTION VALUE="' . $charid . '">' . $charname . '</OPTION>';
}
echo '</SELECT><INPUT TYPE="SUBMIT" VALUE="Choose Toon"></FORM>';
?>