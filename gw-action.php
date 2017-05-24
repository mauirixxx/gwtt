<?php
$toonid = $_POST['playerid'];
$action = $_POST['gwaction'];
if ($action == 1){
	echo '<body onload="javascript:submitForm()">';
	echo '<FORM METHOD="POST" ACTION="gw-record.php" NAME="record"><INPUT TYPE="HIDDEN" NAME="playerid" VALUE="'. $toonid . '"><INPUT TYPE="SUBMIT" ID="clkRecord"></FORM></BODY>';
} else if ($action == 2){
	echo '<body onload="javascript:submitForm()">';
	echo '<FORM METHOD="POST" ACTION="gw-pull.php" NAME="insert"><INPUT TYPE="HIDDEN" NAME="cname" VALUE="' . $toonid . '"><INPUT TYPE="SUBMIT" ID="clkInsert"></FORM></BODY>';
} else {
	echo 'You shouldn\'t be seeing this, something went horribly horribly wrong!';
}
?>