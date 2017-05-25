<?php
// tie everything together from here hopefully, might not even need the connection info?
session_start();
if (isset($_SESSION['userid']) && ($_SESSION['access'])){
	echo 'Proceed to character selection <A HREF="gw-toon.php">here</A><BR>'; //really should automate this
} else {
	echo '<TITLE>Login Required</TITLE><BODY>';
	echo '<CENTER><FORM ACTION="gw-login.php" METHOD="POST">Username:<INPUT TYPE="TEXT" NAME="username" SIZE="20"><BR />';
	echo 'Password:<INPUT TYPE="PASSWORD" NAME="password" SIZE="20"><BR />';
	echo '<INPUT TYPE="SUBMIT" VALUE="Login ..."></FORM></CENTER>';
}
?>
