<!DOCTYPE html>
<HTML>
<HEAD>
<link rel="stylesheet" type="text/css" href="gw-style.css">
<TITLE>Location Selection</TITLE>
</HEAD>
<?php
session_start();
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$playerid = $_SESSION['playerid'];
$profcolor = $_SESSION['profcolor'];
if ($con->connect_errno > 0){
	die ('Unable to connect to database [' . $db->connect_errno . ']');
}
$sqlmaploc = "SELECT treasuredata.treasureid, treasuredata.location FROM treasuredata";
if (!$resultmap = $con->query($sqlmaploc)){
	die ('There was an error running the query [' . $con->error . ']');
}
echo '<STYLE TYPE="TEXT/CSS" MEDIA="SCREEN">body { background-color: ' . $profcolor . '; }</STYLE>';
echo '<BODY><CENTER><FORM METHOD="POST" ACTION="gw-record.php">';
echo '<SELECT NAME="locationid" onchange="this.form.submit()">';
echo '<OPTION SELECTED DISABLED>Select a map location</OPTION>';
while ($rowmap = $resultmap->fetch_array()){
	$locname = $rowmap['location'];
	$locid = $rowmap['treasureid'];
	echo '<OPTION VALUE="' . $locid . '">' . $locname . '</OPTION>';
}
echo '</SELECT><NOSCRIPT><INPUT TYPE="SUBMIT" VALUE="Choose Map Location"></NOSCRIPT></FORM></CENTER><BR />';
echo '<CENTER><FORM METHOD="POST" ACTION="gw-toon.php"><INPUT TYPE="HIDDEN" NAME="playeridid" VALUE="0"><INPUT TYPE="SUBMIT" VALUE="Return to character selection"></FORM>';
?>
<BR /><BR /><CENTER><FORM METHOD="POST" ACTION="gw-logout.php"><INPUT TYPE="HIDDEN" NAME="logout"><INPUT TYPE="SUBMIT" VALUE="Logout"></FORM></CENTER>
</BODY>
</HTML>
