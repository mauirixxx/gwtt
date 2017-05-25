<?php
session_start();
if (isset($_POST['logout'])){
	session_unset();
	session_destroy();
	header("refresh:5;url=gw-login.php");
	echo '<CENTER>You have been logged out ...<BR />Returning to login screen in a few seconds</CENTER>';
} else {
	echo 'Something went wrong, you haven\'t been logged out!';
}
?>