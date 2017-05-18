<?php
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$cnameid = mysqli_real_escape_string($con, $_GET['toonid']); //need to sanitize this input somehow
if ($con->connect_errno > 0){
	die ('Unable to connect to database [' . $db->connect_errno . ']');
}
$sql = "SELECT * FROM `history` WHERE `charname` = '$cnameid' ORDER BY `historydate` ASC";
if (!$result = $con->query($sql)){
	die ('There was an error running the query [' . $con->error . ']');
}
if (mysqli_num_rows($result) > 0) {
	while ($row = $result->fetch_array()){
		echo 'On ' . $row['historydate'] . ', "' . $row['charname'] . '" got ' . $row['goldrec'] . 'GP and ';
		if ($row['itemtype'] == "Rune") {
			echo 'a ' . $row['itemtype'] . ' of ' . $row['runetype'];
		} else {
			if (is_null($row['material'])) {
				echo 'a ' . $row['itemrarity'] . ' r' . $row['itemreq'] . ' ' . $row['itemattribute'] . ' ' . $row['itemtype'] . ' named something stupid';
			} else {
				echo 'a ' . $row['material'];
			}
		}
		echo ' at (insert SQL code here) <BR />!';
	}
} else {
	echo 'no data to display for that character';
}
?>

<TITLE>Treasure Dates</TITLE>
