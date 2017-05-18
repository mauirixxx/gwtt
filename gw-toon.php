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
while ($row = $result->fetch_array()){
	echo 'Please select a character to continue: <A HREF="gw-pull.php?toonid=' . $row['charname'] . '">' . $row['charname'] . '</A><BR />';
}
?>
