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
			$runeid = $row['runetype'];
			$sqlrune = "SELECT listrunes.`runeid`, listrunes.`runes` FROM listrunes WHERE listrunes.`runeid` = $runeid";
			if (!$result2 = $con->query($sqlrune)){
				die ('There was an error running the query [' . $con->error . ']');
			}
			while ($row2 = $result2->fetch_array()){
				echo 'a rune of ' . $row2['runes'];
			}
		} else {
			if (is_null($row['material'])) {
				$itemrarity = $row['itemrarity'];
				$itemattr = $row['itemattribute'];
				$itemweap = $row['itemtype'];
				$sqlrare = "SELECT listrarity.* FROM listrarity WHERE listrarity.`rareid` = $itemrarity";
				$sqlattr = "SELECT listattribute.* FROM listattribute WHERE listattribute.`weapattrid` = $itemattr";
				$sqlweap = "SELECT listtype.* FROM listtype WHERE listtype.`weaponid` = $itemweap";
				if (!$resultrarity = $con->query($sqlrare)){
					die ('There was an error running the query [' . $con->error . ']');
				}
				while ($row3 = $resultrarity->fetch_array()){
					echo 'a ' . $row3['rarity'];
				}
				echo ' r' . $row['itemreq'];
				if (!$resulattr = $con->query($sqlattr)){
					die ('There was an error running the query [' . $con->error . ']');
				}
				while ($row4 = $resultattr->fetch_array()){
					echo $row4['weaponattribute'];
				}
				echo ' ' . $row['itemtype'];
				if (!$resultweap = $con->query($sqlweap)){
					die ('There was an error running the query [' . $con->error . ']');
				}
				while ($row5 = $resultweap->fetch_array()){
					echo ' ' . $row5['weapontype'];
				}
				echo ' named ' . $row['itemname'];
			} else {
				echo 'a ' . $row['material'];
			}
		}
		echo ' at <A HREF="' . $row['wikilink'] . '">' . $row['location'] . '</A><BR />';
	}
} else {
	echo 'There is no data to display for that character yet';
}
?>
<BR />
Return to <A HREF="gw-toon.php">character selection</A> page
</BODY>