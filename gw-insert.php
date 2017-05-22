<?php
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
#non-section specific POST data here
$gold = mysqli_real_escape_string($con, $_POST['droppedgold']); //how much gold dropped
$droptype = mysqli_real_escape_string($con, $_POST['droptype']); //this dictates if the drop was a weapon/rune/material
$locid = mysqli_real_escape_string($con, $_POST['location']); //this is `treasurelocation`.`treasureid` in the database
if ($droptype == 1){
	$rarity = mysqli_real_escape_string($con, $_POST['rare']);
	echo 'a '. $rarity . ' drop was a weapon!<BR />';
} else if ($droptype == 2){
	echo 'drop was a rare material!<BR />';
} else if ($droptype == 3){
	echo 'drop was a rune!<BR />';
} else {
	echo 'No data was sent!<BR />';
}
# all of the below will go away soon
echo 'The locid variable is set to ' . $locid . '<BR />';
echo 'The amount of gold dropped was ' . $gold . '<BR />';
echo '<BR />Return to <A HREF="gw-record.php">data recording</A> page';
?>