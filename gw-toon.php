<!DOCTYPE html>
<HTML>
<HEAD>
<link rel="stylesheet" type="text/css" href="gw-style.css">
<?php
session_start();
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$userid = $_SESSION['userid'];
$whattoon = mysqli_real_escape_string($con, $_POST['playerid']);
if ($con->connect_errno > 0){
	die ('Unable to connect to database [' . $db->connect_errno . ']');
}
if (!$_SESSION['userid']){
	echo '<TITLE>Please login first</TITLE></HEAD><BODY>';
	echo '<CENTER><FORM ACTION="gw-login.php" METHOD="POST">Username:<INPUT TYPE="TEXT" NAME="username" SIZE="20"><BR />';
	echo 'Password:<INPUT TYPE="PASSWORD" NAME="password" SIZE="20"><BR />';
	echo '<INPUT TYPE="SUBMIT" VALUE="Login ..."></FORM></CENTER>';
} else {
	if ($whattoon == "0" or $whattoon == ""){
		$sql = "SELECT playerid, charname FROM `playername` WHERE `userid` = '$userid' ORDER BY `charname` ASC"; //need to make userid a variable
		if (!$result = $con->query($sql)){
			die ('There was an error running the query [' . $con->error . ']');
		}
		echo '<TITLE>Character Selection</TITLE></HEAD><BODY>';
		echo '<CENTER><FORM METHOD="POST">';
		echo '<SELECT NAME="playerid" onchange="this.form.submit()">';
		echo '<OPTION SELECTED DISABLED>Select a Character</OPTION>';
		while ($row = $result->fetch_array()){
			$charid = $row['playerid'];
			$charname = $row['charname'];
			echo '<OPTION VALUE="' . $charid . '">' . $charname . '</OPTION>';
		}
		echo '</SELECT><NOSCRIPT><INPUT TYPE="SUBMIT" VALUE="Choose Toon"></NOSCRIPT></FORM><BR /><BR />';
		echo '<FORM ACTION="gw-create.php"><INPUT TYPE="SUBMIT" VALUE="Add a toon"></FORM></CENTER>';
	} else {
		$sqltoon = "SELECT charname, profcolor from `playername` WHERE playerid = $whattoon";
		if (!$result2 = $con->query($sqltoon)){
			die ('There was an error running the query [' . $con->error . ']');
		}
		while ($row2 = $result2->fetch_array()){
			$charactername = $row2['charname'];
			$profcolor = $row2['profcolor'];
			$_SESSION['profcolor'] = $profcolor;
			echo '<TITLE>' . $charactername . '</TITLE><BODY>';
			echo '<STYLE TYPE="TEXT/CSS" MEDIA="SCREEN">body { background-color: ' . $profcolor . '; }</STYLE>';
		}
		echo '<CENTER><FORM METHOD="POST" ACTION="gw-action.php">';
		$_SESSION['playerid'] = $whattoon;
		echo '<FIELDSET CLASS="radiogroup"><LEGEND>Select your course of action</LEGEND><UL CLASS="radio">';
		echo '<LI style="text-align:left;"><INPUT TYPE="RADIO" NAME="gwaction" VALUE="1">Record loot info</LI>';
		echo '<LI style="text-align:left;"><INPUT TYPE="RADIO" NAME="gwaction" VALUE="2">View Character loot history</LI>';
		echo '</UL></FIELDSET>';
		echo '<INPUT TYPE="SUBMIT" VALUE="Choose action"></FORM><BR /><BR /><FORM METHOD="POST" ACTION="gw-toon.php"><INPUT TYPE="HIDDEN" NAME="cnameid" VALUE="0"><INPUT TYPE="SUBMIT" VALUE="Return to character selection"></FORM></CENTER>';
	}
	echo '<BR /><BR /><CENTER><FORM METHOD="POST" ACTION="gw-logout.php"><INPUT TYPE="HIDDEN" NAME="logout"><INPUT TYPE="SUBMIT" VALUE="Logout"></FORM></CENTER>';
}
?>
</BODY>
</HTML>