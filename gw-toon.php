<?php
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$userid = 1; //need to actually pull this info from cookie/session (preferable)
$whattoon = mysqli_real_escape_string($con, $_POST['cname']);
if ($con->connect_errno > 0){
	die ('Unable to connect to database [' . $db->connect_errno . ']');
}
$sql = "SELECT playerid, charname FROM `playername` WHERE `userid` = '1' ORDER BY `charname` ASC"; //need to make userid a variable
if (!$result = $con->query($sql)){
	die ('There was an error running the query [' . $con->error . ']');
}
if ($whattoon == "0"){
	echo '<CENTER><FORM METHOD="POST">';
	echo '<SELECT NAME="cname" onchange="this.form.submit()">>';
	echo '<OPTION SELECTED DISABLED>Select a Character</OPTION>';
	while ($row = $result->fetch_array()){
		$charid = $row['playerid'];
		$charname = $row['charname'];
		echo '<OPTION VALUE="' . $charid . '">' . $charname . '</OPTION>';
	}
	echo '</SELECT><NOSCRIPT><INPUT TYPE="SUBMIT" VALUE="Choose Toon"></NOSCRIPT></FORM></CENTER>';
} else {
	echo '<CENTER><FORM METHOD="POST" ACTION="gw-action.php">';
	echo '<INPUT TYPE="HIDDEN" NAME="playerid" VALUE=' . $whattoon . '">';
	echo '<LEGEND>Select your course of action</LEGEND><UL>';
	echo '<LI><INPUT TYPE="RADIO" NAME="gwaction" VALUE="1">Record loot info</LI><LI><INPUT TYPE="RADIO" NAME="gwaction" VALUE="2">View Character loot history</LI></UL>';
	echo '<INPUT TYPE="SUBMIT" VALUE="Choose action"></FORM><BR /><BR /><FORM METHOD="POST" ACTION="w-toon.php"><INPUT TYPE="HIDDEN" NAME="cname" VALUE="0"><INPUT TYPE="SUBMIT" VALUE="Return to character selection"></FORM></CENTER>';
}
?>