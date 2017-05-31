<!DOCTYPE html>
<HTML>
<HEAD>
<link rel="stylesheet" type="text/css" href="gw-style.css">
<TITLE>Logging out</TITLE>
</HEAD>
<BODY>
<?php
session_start();
if (isset($_POST['logout'])){
	session_unset();
	session_destroy();
	header("refresh:3;url=gw-index.php");
	echo '<CENTER>You have been logged out ...<BR />Returning to login screen in a few seconds</CENTER>';
} else {
	echo '<CENTER>Something went wrong, you haven\'t been logged out!<BR /><BR />Please click <A HREF="gw-logout.php" CLASS="navlink">HERE</A> to try again</CENTER>';
}
?>
</BODY>
</HTML>