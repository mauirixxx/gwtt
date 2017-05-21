<?php
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
//post data section
$droptype = mysqli_real_escape_string($con, $_POST['droptype']); //this dictates if the drop was a weapon/rune/material
$locid = mysqli_real_escape_string($con, $_POST['location']); //this is `treasurelocation`.`treasureid` in the database
echo 'The droptype variable is set to ' . $droptype . '<BR />';
echo 'The locid variable is set to ' . $locid . '<BR />';
echo 'Return to <A HREF="gw-record.php">data recording</A> page';
?>