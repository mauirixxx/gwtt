<?php
$toonid = $_POST['playerid'];
$action = $_POST['gwaction'];
echo'<TITLE>Redirecting ...</TITLE>';
if ($action == 1){ //insert dropped items data
	echo '<BODY onload="document.record.submit()">';
	echo '<FORM METHOD="POST" ACTION="gw-record.php" NAME="record"><INPUT TYPE="HIDDEN" NAME="playerid" VALUE="'. $toonid . '"><INPUT TYPE="SUBMIT" ID="clkRecord"></FORM></BODY>';
} else if ($action == 2){ //view history of dropped items
	echo '<BODY onload="document.insert.submit()">';
	echo '<FORM METHOD="POST" ACTION="gw-pull.php" NAME="insert"><INPUT TYPE="HIDDEN" NAME="cnameid" VALUE="' . $toonid . '"><INPUT TYPE="SUBMIT" ID="clkInsert"></FORM></BODY>';
} else {
	echo 'You shouldn\'t be seeing this, something went horribly horribly wrong!';
}
?>