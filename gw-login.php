<!DOCTYPE html>
<HTML>
<HEAD>
<?php
session_start();
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$username = mysqli_real_escape_string($con, $_POST['username']);
$password = mysqli_real_escape_string($con, $_POST['password']);
$password = md5($password);
if ($con->connect_errno > 0){
	die ('Unable to connect to database [' . $db->connect_errno . ']');
}
$sqllogin = "SELECT * FROM users WHERE users.username = '$username' and password = '$password'";
if ($result = $con->query($sqllogin)){
	$row_cnt = mysqli_num_rows($result);
	if ($row_cnt > 0){
		while ($row = $result->fetch_array()){
			$uname = $row['username'];
			$uid = $row['userid'];
			$access = $row['access'];
			$_SESSION['username'] = $uname;
			$_SESSION['userid'] = $uid;
			$_SESSION['access'] = $access;
		}
		header("refresh:3;url=gw-index.php");
		echo '<TITLE>Logged in</TITLE></HEAD><BODY><CENTER>';
		echo 'You have successfully logged in ...<BR />Returning to index in a few seconds</CENTER>';
	} else {
		echo '<TITLE>Invalid login</TITLE></HEAD><BODY><CENTER>';
		echo 'That was not a valid username or password!<BR /><BR />';
		echo 'Please try again <A HREF="gw-index.php">here</A></CENTER>';
	}
}
?>
</BODY>
</HTML>