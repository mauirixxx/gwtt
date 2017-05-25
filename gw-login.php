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
echo 'broke something above the sql statement!<BR>';
$sqllogin = "SELECT * FROM users WHERE users.username = '$username' and password = '$password'";
if ($result = $con->query($sqllogin)){
	if ($result->fetchColumn() > 0){
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
		echo 'Login failed - please try again <A HREF="gw-index.php">here</A>';
	}
}
echo 'Proceed to character selection <A HREF="gw-toon.php">here</A><BR>'; //really should automate this
?>