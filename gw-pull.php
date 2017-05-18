<?php
$cnameid = $_GET['toonid']; //need to sanitize this input somehow
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if ($con->connect_errno > 0){
	die ('Unable to connect to database [' . $db->connect_errno . ']');
}
$sql = "SELECT * FROM `history` WHERE `charname` = '$cnameid' ORDER BY `historydate` ASC";
if (!$result = $con->query($sql)){
	die ('There was an error running the query [' . $con->error . ']');
}
while ($row = $result->fetch_array()){
	if ($row['charname'] == ''){
		echo 'No data available for $cnameid';
	} else {
		echo 'On ' . $row['historydate'] . ', "' . $row['charname'] . '" got ' . $row['goldrec'] . 'GP and ';
		if (is_null($row['material'])) {
			echo 'a ' . $row['itemrarity'] . ' req' . $row['itemreq'] . ' ' . $row['itemattribute'] . ' ' . $row['itemtype'] . ' named something stupid <BR />';
		} else {
			echo 'a ' . $row['material'] . '!<BR />';
		} //add another else statement to cover RUNES here
	}
}
?>

<TITLE>Treasure Dates</TITLE>
