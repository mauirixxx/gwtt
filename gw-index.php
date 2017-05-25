<?php
session_start();
if (isset($_SESSION['userid']) && ($_SESSION['access'])){
	$uname = $_SESSION['username'];
	echo '<HTML><HEAD><TITLE>Welcome, ' . $uname . '</TITLE></HEAD><BODY><CENTER>';
	echo 'Proceed to character selection <A HREF="gw-toon.php">here</A><BR />';
	echo 'Create a new character to record <A HREF="gw-create.php">here</A><BR /></CENTER>';
} else {
	echo '<HTML><HEAD><TITLE>Login Required</TITLE></HEAD><BODY>';
	echo '<CENTER><FORM ACTION="gw-login.php" METHOD="POST">Username:<INPUT TYPE="TEXT" NAME="username" SIZE="20"><BR />';
	echo 'Password:<INPUT TYPE="PASSWORD" NAME="password" SIZE="20"><BR />';
	echo '<INPUT TYPE="SUBMIT" VALUE="Login ..."></FORM></CENTER>';
}
echo '</BODY></HTML>';
?>
