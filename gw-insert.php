<!DOCTYPE html>
<HTML>
<HEAD>
<link rel="stylesheet" type="text/css" href="gw-style.css">
<TITLE>Inserting Data</TITLE>
</HEAD>
<?php
session_start();
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
#non-section specific POST data here
$gold = mysqli_real_escape_string($con, $_POST['droppedgold']); //how much gold dropped
$droptype = mysqli_real_escape_string($con, $_POST['droptype']); //this dictates if the drop was a weapon/rune/material
$locid = mysqli_real_escape_string($con, $_POST['location']); //this is `treasurelocation`.`treasureid` in the database
$toonid = $_SESSION['playerid'];
$uid = $_SESSION['userid'];
$treasdate = mysqli_real_escape_string($con, $_POST['treasuredate']);
if ($droptype == 1){
	$rarity = mysqli_real_escape_string($con, $_POST['rare']);
	$req = mysqli_real_escape_string($con, $_POST['requirement']);
	$attrib = mysqli_real_escape_string($con, $_POST['attribute']);
	$weap = mysqli_real_escape_string($con, $_POST['weapon']);
	$itname = mysqli_real_escape_string($con, $_POST['itemname']);
	$sqlweapins = "INSERT INTO `history` (historydate, userid, charnameid, locationid, goldrec, itemreq, itemtype, itemattribute, itemrarity, itemname) VALUES ('$treasdate', $uid, $toonid, $locid, $gold, $req, $weap, $attrib, $rarity, '$itname')";
	if (!$result = $con->query($sqlweapins)){
		die ('There was an error running the query [' . $con->error . ']');
	}
} else if ($droptype == 2){
	$matid = mysqli_real_escape_string($con, $_POST['rarematerial']);
	$sqlmatins = "INSERT INTO `history` (historydate, userid, charnameid, locationid, goldrec, material) VALUES ('$treasdate', $uid, $toonid, $locid, $gold, $matid)";
	if (!$result = $con->query($sqlmatins)){
		die ('There was an error running the query [' . $con->error . ']');
	}
} else if ($droptype == 3){
	$runeid = mysqli_real_escape_string($con, $_POST['rune']);
	$runerare = mysqli_real_escape_string($con, $_POST['runerarity']);
	$sqlruneins = "INSERT INTO `history` (historydate, userid, charnameid, locationid, goldrec, itemtype, itemrarity, runetype) VALUES ('$treasdate', $uid, $toonid, $locid, $gold, '16', $runerare, $runeid)";
	if (!$result = $con->query($sqlruneins)){
		die ('There was an error running the query [' . $con->error . ']');
	}
} else if ($droptype == 4){
	$itname = mysqli_real_escape_string($con, $_POST['itemname']);
	$sqlnothing = "INSERT INTO `history` (historydate, userid, charnameid, locationid, goldrec, itemname) VALUES ('$treasdate', $uid, $toonid, $locid, $gold, '$itname')";
	if (!$result = $con->query($sqlnothing)){
		die ('There was an error running the query [' . $con->error . ']');
	}
} else {
	exit("Variable droptype was set to ($droptype)");
}
?>
<BODY onload="document.returntotoons.submit()">
<CENTER>
<FORM METHOD="POST" ACTION="gw-toon.php" NAME="returntotoons">
<INPUT TYPE="SUBMIT">
</FORM>
</CENTER>
</BODY>
</HTML>