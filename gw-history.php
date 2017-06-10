<!DOCTYPE html>
<HTML>
<HEAD>
<link rel="stylesheet" type="text/css" href="gw-style.css">
<TITLE>Treasure Data</TITLE>
</HEAD>
<BODY>
<CENTER><TABLE BORDER="0">
<?php
session_start();
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
//$cnameid = mysqli_real_escape_string($con, $_POST['cnameid']); //need to sanitize & validate this input somehow
$cnameid = $_SESSION['playerid'];
if ($con->connect_errno > 0){
	die ('Unable to connect to database [' . $db->connect_errno . ']');
}
$sql = "SELECT history.*, treasurelocation.*, playername.`playerid`, playername.`charname` FROM ((history INNER JOIN treasurelocation ON history.`locationid` = treasurelocation.`treasureid`) INNER JOIN playername ON history.`charnameid` = playername.`playerid`) WHERE history.`charnameid` = '$cnameid' ORDER BY `historydate`,`historyid` ASC";
if (!$result = $con->query($sql)){
	die ('There was an error running the query [' . $con->error . ']');
}
if (mysqli_num_rows($result) > 0) {
	while ($row = $result->fetch_array()){
		echo '<TR><TD>On ' . $row['historydate'] . ', "' . $row['charname'] . '" got ' . $row['goldrec'] . 'GP and ';
		if ($row['itemtype'] == 16) { //this would be a rune
			$runeid = $row['runetype'];
			$sqlrune = "SELECT listrunes.`runeid`, listrunes.`runes` FROM listrunes WHERE listrunes.`runeid` = $runeid";
			if (!$result2 = $con->query($sqlrune)){
				die ('There was an error running the query [' . $con->error . ']');
			}
			while ($row2 = $result2->fetch_array()){
				echo 'a rune of ' . $row2['runes'];
			}
		} else if ($row['itemtype'] == 17) { //nothing dropped, but showing the recorded date
			echo 'nothing dropped at this location on this date';
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
				if (!$resultattr = $con->query($sqlattr)){
					die ('There was an error running the query [' . $con->error . ']');
				}
				while ($row4 = $resultattr->fetch_array()){
					echo ' ' . $row4['weaponattribute'];
				}
				if (!$resultweap = $con->query($sqlweap)){
					die ('There was an error running the query [' . $con->error . ']');
				}
				while ($row5 = $resultweap->fetch_array()){
					echo ' ' . $row5['weapontype'];
				}
				echo ' named ' . $row['itemname'];
			} else {
				$matid = $row['material'];
				$sqlmat = "SELECT material FROM materials WHERE materialid = $matid";
				if (!$resultmats = $con->query($sqlmat)){
					die ('There was an error running the query [' . $con->error . ']');
				}
				while ($row6 = $resultmats->fetch_array()){
					echo 'a ' . $row6['material'];
				}
			}
		}
		echo ' at <A HREF="' . $row['wikilink'] . '" CLASS="navlink">' . $row['location'] . '</A></TD></TR>';
	}
} else {
	echo '<CENTER>There is no data to display for that character yet</CENTER><BR />';
}
?>
</TABLE></CENTER>
<BR />
<CENTER>
<FORM METHOD="POST" ACTION="gw-toon.php">
<INPUT TYPE="HIDDEN" NAME="cnameid" VALUE="0">
<INPUT TYPE="SUBMIT" VALUE="Return to character selection">
</FORM>
</CENTER>
</BODY>
</HTML>