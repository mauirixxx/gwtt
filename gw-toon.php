<?php
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$userid = $_SESSION['userid']; //need to actually pull this info from cookie/session (preferable)
$whattoon = mysqli_real_escape_string($con, $_POST['cnameid']);
if ($con->connect_errno > 0){
	die ('Unable to connect to database [' . $db->connect_errno . ']');
}
if ($whattoon == "0" or $whattoon == ""){
	$sql = "SELECT playerid, charname FROM `playername` WHERE `userid` = '$userid' ORDER BY `charname` ASC"; //need to make userid a variable
	if (!$result = $con->query($sql)){
		die ('There was an error running the query [' . $con->error . ']');
	}
	echo '<TITLE>Character Selection</TITLE><BODY>';
	echo '<CENTER><FORM METHOD="POST">';
	echo '<SELECT NAME="cnameid" onchange="this.form.submit()">';
	echo '<OPTION SELECTED DISABLED>Select a Character</OPTION>';
	while ($row = $result->fetch_array()){
		$charid = $row['playerid'];
		$charname = $row['charname'];
		echo '<OPTION VALUE="' . $charid . '">' . $charname . '</OPTION>';
	}
	echo '</SELECT><NOSCRIPT><INPUT TYPE="SUBMIT" VALUE="Choose Toon"></NOSCRIPT></FORM></CENTER></BODY>';
} else {
	$sqltoon = "SELECT charname from `playername` WHERE playerid = $whattoon";
	if (!$result2 = $con->query($sqltoon)){
		die ('There was an error running the query [' . $con->error . ']');
	}
	while ($row2 = $result2->fetch_array()){
		$charactername = $row2['charname'];
		echo '<TITLE>' . $charactername . '</TITLE><BODY>';
	}
	echo '<CENTER><FORM METHOD="POST" ACTION="gw-action.php">';
	echo '<INPUT TYPE="HIDDEN" NAME="playerid" VALUE="' . $whattoon . '">';
	echo '<FIELDSET CLASS="radiogroup"><LEGEND>Select your course of action</LEGEND><UL CLASS="radio">';
	echo '<LI style="text-align:left;"><INPUT TYPE="RADIO" NAME="gwaction" VALUE="1">Record loot info</LI><LI style="text-align:left;"><INPUT TYPE="RADIO" NAME="gwaction" VALUE="2">View Character loot history</LI></UL></FIELDSET>';
	echo '<INPUT TYPE="SUBMIT" VALUE="Choose action"></FORM><BR /><BR /><FORM METHOD="POST" ACTION="gw-toon.php"><INPUT TYPE="HIDDEN" NAME="cnameid" VALUE="0"><INPUT TYPE="SUBMIT" VALUE="Return to character selection"></FORM></CENTER></BODY>';
}
echo '<BR /><BR /><CENTER><FORM METHOD="POST" ACTION="gw-logout.php"><INPUT TYPE="HIDDEN" NAME="logout"><INPUT TYPE="SUBMIT" VALUE="Logout"></FORM></CENTER>';
?>