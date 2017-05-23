<?php
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
#non-section specific POST data here
$gold = mysqli_real_escape_string($con, $_POST['droppedgold']); //how much gold dropped
$droptype = mysqli_real_escape_string($con, $_POST['droptype']); //this dictates if the drop was a weapon/rune/material
$locid = mysqli_real_escape_string($con, $_POST['location']); //this is `treasurelocation`.`treasureid` in the database
$toonid = mysqli_real_escape_string($con, $_POST['chartoon']); //this is the id of the character doing the hunting
$treasdate = mysqli_real_escape_string($con, $_POST['treasuredate']); //
if ($droptype == 1){
	$rarity = mysqli_real_escape_string($con, $_POST['rare']);
	$req = mysqli_real_escape_string($con, $_POST['requirement']);
	$attrib = mysqli_real_escape_string($con, $_POST['attribute']);
	$weap = mysqli_real_escape_string($con, $_POST['weapon']);
	$itname = mysqli_real_escape_string($con, $_POST['itemname']);
	$sqlweapins = "INSERT INTO `history` (historydate, charnameid, locationid, goldrec, itemreq, itemtype, itemattribute, itemrarity, itemname) VALUES ('$treasdate', $toonid, $locid, $gold, $req, $weap, $attrib, $rarity, '$itname')";
	if (!$result = $con->query($sqlweapins)){
		die ('There was an error running the query [' . $con->error . ']');
	}
} else if ($droptype == 2){
	echo 'drop was a rare material!<BR />';
} else if ($droptype == 3){
	$runeid = mysqli_real_escape_string($con, $_POST['rune']);
	$runerare = mysqli_real_escape_string($con, $_POST['runerarity']);
	$sqlruneins = "INSERT INTO `history` (historydate, charnameid, locationid, goldrec, itemtype, itemrarity, runetype) VALUES ('$treasdate', $toonid, $locid, $gold, '16', $runerare, $runeid)";
	if (!$result = $con->query($sqlruneins)){
		die ('There was an error running the query [' . $con->error . ']');
	}
} else {
	echo 'No data was sent!<BR />';
}
echo '<BR />Return to <A HREF="gw-record.php">data recording</A> page';
?>