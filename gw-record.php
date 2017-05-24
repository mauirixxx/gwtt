<TITLE>What Dropped?</TITLE>
<?php
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
#$toonid = mysqli_real_escape_string($con, $_POST['playerid']); //enable this after character selection is working
$toonid = '3'; //delete this line after character selection is finished/working
#$location = mysqli_real_escape_string($con, $_POST['locationid']); //enable this after location selection is working
$location = 4; //delete this line after location selection is finished/working
$whatdropped = mysqli_real_escape_string($con, $_POST['gwdrop']);
if ($con->connect_errno > 0){
	die ('Unable to connect to database [' . $db->connect_errno . ']');
}
echo 'At ';
$sqlmaplocation = "SELECT * FROM `treasurelocation` WHERE `treasureid` = $location";
if (!$result = $con->query($sqlmaplocation)){
	die ('There was an error running the query [' . $con->error . ']');
}
while ($row = $result->fetch_array()){
	$locname = $row['location'];
	$loclink = $row['wikilink'];
	$locid = $row['treasureid'];
	echo '<A HREF="' . $loclink . '">' . $locname . '</A>';
}
if ($whatdropped == "1"){
	echo '<FORM METHOD="POST" ACTION="gw-insert.php">';
	echo 'on <INPUT NAME="treasuredate" TYPE="DATE" PLACEHOLDER="2006-10-26"> a ';
	//code for white blue purple etc
	$sqlweaprare = "SELECT * FROM `listrarity` ORDER BY `rareid` ASC";
	if (!$result = $con->query($sqlweaprare)){
		die ('There was an error running the query [' . $con->error . ']');
	}
	echo '<SELECT NAME="rare">';
	while ($row = $result->fetch_array()){
		$rareid = $row['rareid'];
		$rarity = $row['rarity'];
		echo '<OPTION VALUE="' . $rareid . '">' . $rarity . '</OPTION>';
	}
	echo '</SELECT>, ';
	//code for weapon attribute requirment
	$sqlweapreq = "SELECT * FROM `listreq` ORDER BY `req` ASC";
	if (!$result = $con->query($sqlweapreq)){
		die ('There was an error running the query [' . $con->error . ']');
	}
	echo 'req<SELECT NAME="requirement">';
	while ($row = $result->fetch_array()){
		$reqid = $row['req'];
		echo '<OPTION VALUE="' . $reqid . '">' . $reqid . '</OPTION>';
	}
	echo '</SELECT>';
	//code for what attribute the weapon is (command, axe mastery, energy storage, etc
	$sqlweapattr = "SELECT * FROM `listattribute` ORDER BY `weapattrid` ASC";
	if (!$result = $con->query($sqlweapattr)){
		die ('There was an error running the query [' . $con->error . ']');
	}
	echo '<SELECT NAME="attribute">';
	while ($row = $result->fetch_array()){
		$attrid = $row['weapattrid'];
		$weapattr = $row['weaponattribute'];
		echo '<OPTION VALUE="' . $attrid . '">' . $weapattr . '</OPTION>';
	//need to add a nested while loop, to preselect the weapon with the attribute, or somehow java it? An r9 Axe of Energy Storage combo doesn't exist.
	}
	echo '</SELECT>';
	//code for what the weapon is - staff, dagger, scythe, wand, sword, etc
	$sqlweaptype = "SELECT * FROM `listtype` ORDER BY `weaponid` ASC";
	if (!$result = $con->query($sqlweaptype)){
		die ('There was an error running the query [' . $con->error . ']');
	}
	echo '<SELECT NAME="weapon">';
	while ($row = $result->fetch_array()){
		$typeid = $row['weaponid'];
		$weapon = $row['weapontype'];
		echo '<OPTION VALUE="' . $typeid . '">' . $weapon . '</OPTION>';
	}
	echo '</SELECT> called the <INPUT TYPE="TEXT" NAME="itemname" MAXLENGTH="100" SIZE="40">';
	echo ' and <INPUT TYPE="NUMBER" NAME="droppedgold" SIZE="4" MIN="1" MAX="9999"> gold pieces.';
	echo '<INPUT TYPE="HIDDEN" NAME="droptype" VALUE="1"><INPUT TYPE="HIDDEN" NAME="location" VALUE="' . $locid .'">';
	echo '<INPUT TYPE="HIDDEN" NAME="chartoon" VALUE="' . $toonid .'">';
	echo ' <BR /><CENTER><INPUT TYPE="SUBMIT" VALUE="Click me!"></FORM></CENTER><BR />';
} else if ($whatdropped == "2"){
	echo '<FORM METHOD="POST" ACTION="gw-insert.php">';
	echo 'on <INPUT NAME="treasuredate" TYPE="DATE" PLACEHOLDER="2006-10-26"> a ';
	//code for what rare material dropped
	$sqlraremat = "SELECT * FROM `materials` ORDER BY `materialid` ASC";
	if (!$result = $con->query($sqlraremat)){
		die ('There was an error running the query [' . $con->error . ']');
	}
	echo '<SELECT NAME="rarematerial">';
	while ($row = $result->fetch_array()){
		$matid = $row['materialid'];
		$raremat = $row['material'];
		echo '<OPTION VALUE="' . $matid . '">' . $raremat . '</OPTION>';
	}
	echo '</SELECT> ';
	echo ' and <INPUT TYPE="NUMBER" NAME="droppedgold" SIZE="4" MIN="1" MAX="9999"> gold pieces.';
	echo '<INPUT TYPE="HIDDEN" NAME="droptype" VALUE="2"><INPUT TYPE="HIDDEN" NAME="location" VALUE="' . $locid .'">';
	echo ' <BR /><CENTER><INPUT TYPE="SUBMIT" VALUE="Click me!"></FORM></CENTER><BR />';
} else if ($whatdropped == "3"){
	echo '<FORM METHOD="POST" ACTION="gw-insert.php">';
	echo 'on <INPUT NAME="treasuredate" TYPE="DATE" PLACEHOLDER="2006-10-26"> a ';
	echo '<SELECT NAME="runerarity"><OPTION VALUE="2">Blue</OPTION><OPTION VALUE="3">Purple</OPTION><OPTION VALUE="4">Gold</OPTION></SELECT> ';
	//code for what rune dropped
	$sqlrune = "SELECT * FROM `listrunes` ORDER BY `runeid` ASC";
	if (!$result = $con->query($sqlrune)){
		die ('There was an error running the query [' . $con->error . ']');
	}
	echo 'rune of <SELECT NAME="rune">';
	while ($row = $result->fetch_array()){
		$runeid = $row['runeid'];
		$rune = $row['runes'];
		echo '<OPTION VALUE="' . $runeid . '">' . $rune . '</OPTION>';
	}
	echo '</SELECT> ';
	echo ' and <INPUT TYPE="NUMBER" NAME="droppedgold" SIZE="4" MIN="1" MAX="9999"> gold pieces.';
	echo '<INPUT TYPE="HIDDEN" NAME="droptype" VALUE="3"><INPUT TYPE="HIDDEN" NAME="location" VALUE="' . $locid .'">';
	echo '<INPUT TYPE="HIDDEN" NAME="chartoon" VALUE="' . $toonid .'">';
	echo ' <BR /><CENTER><INPUT TYPE="SUBMIT" VALUE="Click me!"></FORM></CENTER><BR />';
} else {
	echo '<FORM METHOD="POST"><SELECT NAME="gwdrop" onchange="this.form.submit()">';
	echo '<OPTION SELECTED DISABLED>choose one</OPTION>';
	echo '<OPTION VALUE="1">weapon</OPTION>';
	echo '<OPTION VALUE="2">material</OPTION>';
	echo '<OPTION VALUE="3">rune</OPTION></SELECT>';
	echo '<NOSCRIPT><INPUT TYPE="SUBMIT" VALUE="SUBMIT"></NOSCRIPT></FORM>';
}
echo 'Reload the page with no POST data? <A HREF="gw-record.php">RELOAD</A><BR /><BR />'; //this needs to go away soon
echo 'Go to <A HREF="gw-toon.php">character selection</A>'; //need to make this a form to preselect previously selected character
?>