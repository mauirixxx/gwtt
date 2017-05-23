<TITLE>Treasure Data</TITLE>
<BODY>
<?php
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$cnameid = mysqli_real_escape_string($con, $_POST['cname']); //need to sanitize & validate this input somehow
if ($con->connect_errno > 0){
	die ('Unable to connect to database [' . $db->connect_errno . ']');
}
#$sql = "SELECT * FROM `history` WHERE `charname` = '$cnameid' ORDER BY `historydate` ASC"; //old sql statement that works currently
#$sql = "SELECT history.*, treasurelocation.* FROM history LEFT OUTER JOIN treasurelocation ON history.`locationid` = treasurelocation.`treasureid` WHERE history.`charname` = '$cnameid' ORDER BY `historydate` ASC"; //round 2 of SQL queries, saving this since it still works
$sql = "SELECT history.*, treasurelocation.*, playername.`playerid`, playername.`charname` FROM ((history INNER JOIN treasurelocation ON history.`locationid` = treasurelocation.`treasureid`) INNER JOIN playername ON history.`charnameid` = playername.`playerid`) WHERE history.`charnameid` = '$cnameid' ORDER BY `historydate` ASC";
if (!$result = $con->query($sql)){
	die ('There was an error running the query [' . $con->error . ']');
}
if (mysqli_num_rows($result) > 0) {
	while ($row = $result->fetch_array()){
		echo 'On ' . $row['historydate'] . ', "' . $row['charname'] . '" got ' . $row['goldrec'] . 'GP and ';
		if ($row['itemtype'] == 16) { //this would be a rune
			echo 'a rune of ' . $row['runetype'];
			$runeid = '6';
			$sqlrune = "SELECT listrunes.`runeid`, listrunes.`runes` FROM listrunes WHERE listrunes.`runeid` = $runeid";
			$runeresults = mysqli_query($con, $sqlrune);
			echo 'Results of mapping runeid to runes: ' $runeresults;
		} else {
			if (is_null($row['material'])) {
				echo 'a ' . $row['itemrarity'] . ' r' . $row['itemreq'] . ' ' . $row['itemattribute'] . ' ' . $row['itemtype'] . ' named ' . $row['itemname'] . ''; //itemtype changed, need to convert itemtype to something readable
			} else {
				echo 'a ' . $row['material'];
			}
		}
		echo ' at <A HREF="' . $row['wikilink'] . '">' . $row['location'] . '</A><BR />';
	}
} else {
	echo 'There is no data to display for that character yet';
}
# test getting rune results without breaking working parts
?>
<BR />
Return to <A HREF="gw-toon.php">character selection</A> page
</BODY>