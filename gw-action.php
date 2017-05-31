<!DOCTYPE html>
<HTML>
<HEAD>
<link rel="stylesheet" type="text/css" href="gw-style.css">
<?php
session_start();
$playerid = $_SESSION['playerid'];
$action = $_POST['gwaction'];
echo'<TITLE>Redirecting ...</TITLE></HEAD>';
if ($action == 1){ //insert dropped items data
	echo '<BODY onload="document.record.submit()">';
	echo '<FORM METHOD="POST" ACTION="gw-location.php" NAME="record"><INPUT TYPE="HIDDEN" NAME="playerid" VALUE="'. $playerid . '"><INPUT TYPE="SUBMIT" ID="clkRecord"></FORM></BODY>';
} else if ($action == 2){ //view history of dropped items
	echo '<BODY onload="document.insert.submit()">';
	echo '<FORM METHOD="POST" ACTION="gw-history.php" NAME="insert"><INPUT TYPE="HIDDEN" NAME="cnameid" VALUE="' . $playerid . '"><INPUT TYPE="SUBMIT" ID="clkInsert"></FORM></BODY>';
} else {
	echo 'You shouldn\'t be seeing this, something went horribly horribly wrong!';
}
?>
</HTML>