<?php
session_start();
$playerid = $_SESSION['playerid'];
$action = $_POST['gwaction'];
echo'<TITLE>Redirecting ...</TITLE>';
if ($action == 1){ //insert dropped items data
	echo '<BODY onload="document.record.submit()">';
	echo '<FORM METHOD="POST" ACTION="gw-location.php" NAME="record"><INPUT TYPE="HIDDEN" NAME="playerid" VALUE="'. $playerid . '"><INPUT TYPE="SUBMIT" ID="clkRecord"></FORM></BODY>';
} else if ($action == 2){ //view history of dropped items
	echo '<BODY onload="document.insert.submit()">';
	echo '<FORM METHOD="POST" ACTION="gw-pull.php" NAME="insert"><INPUT TYPE="HIDDEN" NAME="cnameid" VALUE="' . $playerid . '"><INPUT TYPE="SUBMIT" ID="clkInsert"></FORM></BODY>';
} else if ($action == 3){ //toon creation page here
	echo '<BODY onload="document.createtoon.submit()">';
	echo 'Character creation is currently disabled!<BR><BR>Click <A HREF="gw-index.php">here</A> to go to the main page';
	//echo '<FORM METHOD="POST" ACTION="gw-create.php" NAME="createtoon"><INPUT TYPE="SUBMIT"></FORM></BODY>'; //enable this and modify it when gw-create.php is made
{
	echo 'You shouldn\'t be seeing this, something went horribly horribly wrong!';
}
?>