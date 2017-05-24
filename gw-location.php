<TITLE>Location Selection</TITLE>
<?php
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
//all POST variable data under here
$playerid = mysqli_real_escape_string($con, $_POST['playerid']);
if ($con->connect_errno > 0){
	die ('Unable to connect to database [' . $db->connect_errno . ']');
}
$sqlmaploc = "SELECT treasurelocation.treasureid, treasurelocation.location FROM treasurelocation";
if (!$resultmap = $con->query($sqlmaploc)){
	die ('There was an error running the query [' . $con->error . ']');
}
echo '<BODY><CENTER><FORM METHOD="POST" ACTION="gw-record.php">';
echo '<INPUT TYPE="HIDDEN" NAME="playerid" VALUE="' . $playerid . '">';
echo 'Current playerid is :' . $playerid ' <BR />';
echo '<SELECT NAME="locationid" onchange="this.form.submit()">';
echo '<OPTION SELECTED DISABLED>Select a map location</OPTION>';
while ($rowmap = $resultmap->fetch_array()){
	$locname = $rowmap['location'];
	$locid = $rowmap['treasureid'];
	echo '<OPTION VALUE="' . $locid . '">' . $locname . '</OPTION>';
}
echo '</SELECT><NOSCRIPT><INPUT TYPE="SUBMIT" VALUE="Choose Map Location"></NOSCRIPT></FORM></CENTER></BODY>';
?>