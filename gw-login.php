<?php
session_start();
if (isset($_SESSION['userid']) && ($_SESSION['access'])){
	echo 'Proceed to character selection <A HREF="gw-toon.php">here</A><BR>'; //really should automate this
} else {
	include_once 'gw-connect.php';
	$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
	$username = mysqli_real_escape_string($con, $_POST['username']); //enable this after username form is built
	//$username = 'mauirixxx'; //delete this line after the above is finished
	$password = mysqli_real_escape_string($con, $_POST['password']); //enable this after password form is built
	//$password = 'drx9175l';
	$password = md5($password);
	if ($con->connect_errno > 0){
		die ('Unable to connect to database [' . $db->connect_errno . ']');
	}
	$sqllogin = "SELECT * FROM users WHERE users.username = '$username' and password = '$password'";
	if (!$result = $con->query($sqllogin)){
		die ('There was an error running the query [' . $con->error . ']');
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
}
# keeping the code below for references material only
/* if(isset($_POST["submit"])){
	if(empty($_POST["username"]) || empty($_POST["password"])){
		$error = "Both fields are required.";
	} else {
		// Define $username and $password
		$username=$_POST['username'];
		$password=$_POST['password'];
 
		// To protect from MySQL injection
		$username = stripslashes($username);
		$password = stripslashes($password);
		$username = mysqli_real_escape_string($con, $username);
		$password = mysqli_real_escape_string($con, $password);
		$password = md5($password);
 
		//Check username and password from database
		$sql-login="SELECT userid FROM users WHERE users.username = '$username' and password = '$password'";
		$result=mysqli_query($con,$sql-login);
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
 
		//If username and password exist in our database then create a session.
		//Otherwise echo error.
 
		if(mysqli_num_rows($result) == 1){
			$_SESSION['username'] = $login_user; // Initializing Session
			header("location: gw-toon.php"); // Redirecting To Other Page
		} else {
			$error = "Incorrect username or password.";
		}
	}
} */
?>