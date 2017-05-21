<?php
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
//post data section
$droptype = mysqli_real_escape_string($con, $_POST['droptype']); //this will stay

$rare = $_POST['gwdrop']; //this will all go away soon
echo 'If this worked then there will be a number here: ' . $rare . ' <BR />';
echo 'The droptype variable is set to ' . $droptype . '<BR />';
echo 'Return to <A HREF="gw-record.php">data recording</A> page';
?>