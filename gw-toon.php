<?php
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$userid = 1; //need to actually pull this info from cookie/session (preferable)
$whattoon = mysqli_real_escape_string($con, $_POST['cnameid']);
$nameoftoon = mysqli_real_escape_string($con, $_POST['charactername']);
if ($toonid == ""){
	echo '<TITLE>Choose a character!</TITLE><BODY>';
} else {
	echo '<TITLE>' . $nameoftoon . '</TITLE></BODY>"';
}
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
		echo '<INPUT TYPE="HIDDEN" NAME="charactername" VALUE="' . $charname . '">';
	}
	echo '</SELECT><NOSCRIPT><INPUT TYPE="SUBMIT" VALUE="Choose Toon"></NOSCRIPT></FORM></CENTER>';
} else {
	echo '<CENTER><FORM METHOD="POST" ACTION="gw-action.php">';
	echo '<INPUT TYPE="HIDDEN" NAME="playerid" VALUE=' . $whattoon . '">';
	echo '<FIELDSET CLASS="radiogroup"><LEGEND>Select your course of action</LEGEND><UL CLASS="radio">';
	echo '<LI style="text-align:left;"><INPUT TYPE="RADIO" NAME="gwaction" VALUE="1">Record loot info</LI><LI style="text-align:left;"><INPUT TYPE="RADIO" NAME="gwaction" VALUE="2">View Character loot history</LI></UL></FIELDSET>';
	echo '<INPUT TYPE="SUBMIT" VALUE="Choose action"></FORM><BR /><BR /><FORM METHOD="POST" ACTION="gw-toon.php"><INPUT TYPE="HIDDEN" NAME="cnameid" VALUE="0"><INPUT TYPE="SUBMIT" VALUE="Return to character selection"></FORM></CENTER>';
}
?>