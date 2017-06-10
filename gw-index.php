<!DOCTYPE html>
<HTML>
<HEAD>
<link rel="stylesheet" type="text/css" href="gw-style.css">
<?php
session_start();
if (isset($_SESSION['userid']) && ($_SESSION['access'])){
	$uname = $_SESSION['username'];
	echo '<TITLE>Welcome, ' . $uname . '</TITLE></HEAD><BODY><CENTER>';
	echo 'Proceed to character selection <A HREF="gw-toon.php" CLASS="navlink">here</A><BR />';
	echo 'Create a new character to record <A HREF="gw-create.php" CLASS="navlink">here</A><BR />';
	if ($_SESSION['access'] == 9){
		echo'<BR />Hello admin, please click <A HREF="gw-admin.php">here</A> to access the admin page <BR />';
	}
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
