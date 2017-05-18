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
if (mysqli_num_rows($result) > 0) {
	while ($row = $result->fetch_array()){
		echo 'On ' . $row['historydate'] . ', "' . $row['charname'] . '" got ' . $row['goldrec'] . 'GP and ';
		if ($row['itemtype'] == "Rune") {
			echo 'a ' . $row['itemtype'] . ' of ' . $row['runetype'] . '!<BR />';
		} else {
			if (is_null($row['material'])) {
				echo 'a ' . $row['itemrarity'] . ' req' . $row['itemreq'] . ' ' . $row['itemattribute'] . ' ' . $row['itemtype'] . ' named something stupid <BR />';
			} else {
				echo 'a ' . $row['material'] . '!<BR />';
			}
		} //add another else statement to cover RUNES here. or up there.
	}
} else {
	echo 'no data to display for that character';
}
?>

<TITLE>Treasure Dates</TITLE>
