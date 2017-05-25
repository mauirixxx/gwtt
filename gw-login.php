<?php
session_start();
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$username = mysqli_real_escape_string($con, $_POST['username']); //enable this after username form is built
$password = mysqli_real_escape_string($con, $_POST['password']); //enable this after password form is built
$password = md5($password);
if ($con->connect_errno > 0){
	die ('Unable to connect to database [' . $db->connect_errno . ']');
}
$sqllogin = "SELECT * FROM users WHERE users.username = '$username' and password = '$password'";
if ($result = $con->query($sqllogin)){
	$row_cnt = mysqli_num_rows($result);
	if ($row_cnt > 0){
		echo 'you should be logging in now!<BR>'; //will move the while loop up to here if successful
	} else {
		echo 'That was not a valid username or password!<BR>';
	}
	while ($row = $result->fetch_array()){
		$uname = $row['username'];
		$uid = $row['userid'];
		$access = $row['access'];
		$_SESSION['username'] = $uname;
		$_SESSION['userid'] = $uid;
		$_SESSION['access'] = $access;
		echo 'Your username is ' . $uname . '. Your userid is ' . $uid . '. Your access level is ' . $access . '.<BR />';
	}
} else {
	echo 'Login failed - please try again <A HREF="gw-index.php">here</A>'; //saving this for later
	exit;
}
echo 'Proceed to character selection <A HREF="gw-toon.php">here</A><BR>'; //really should automate this
?>