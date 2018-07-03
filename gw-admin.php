<!DOCTYPE html>
<HTML>
<HEAD>
<link rel="stylesheet" type="text/css" href="gw-style.css">
<?php
session_start();
if (isset($_SESSION['userid']) && ($_SESSION['access'])){
	$uname = $_SESSION['username'];
	echo '<TITLE>Welcome, ' . $uname . '</TITLE></HEAD><BODY><CENTER>';
	if ($_SESSION['access'] < 9){ //need to make this a SQL query instead
		echo'<BR />You don\'t have access to these tools!<BR />';
	} else {
		echo 'Delete a toon <A HREF="gw-deletetoon.php" CLASS="navlink">here</A><BR />'; //page doesn't actually exist yet
		echo 'This will delete a character from a user account, and all of it\'s recorded drop data - this is not reversable!<BR /><BR />';
		echo 'Delete a user <A HREF="gw-deleteuser.php" CLASS="navlink">here</A><BR />'; //page doesn't actually exist yet
		echo 'This will delete the user, all of their associated toons, and associated drop data - this is NOT reversable!<BR /><BR />';
	}
	echo 'Click <A HREF="gw-index.php" CLASS="navlink">HERE</A> to return to the home page!';
} else {
	echo '<TITLE>Login Required</TITLE></HEAD><BODY>';
	echo '<CENTER><FORM ACTION="gw-login.php" METHOD="POST">Username:<INPUT TYPE="TEXT" NAME="username" SIZE="20"><BR />';
	echo 'Password:<INPUT TYPE="PASSWORD" NAME="password" SIZE="20"><BR />';
	echo '<INPUT TYPE="SUBMIT" VALUE="Login ..."></FORM>';
}
?>
</CENTER>
</BODY>
</HTML>