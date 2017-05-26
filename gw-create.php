<!DOCTYPE html>
<HTML>
<HEAD>
<TITLE>Character Creation</TITLE>
</HEAD>
<BODY>
<?php
session_start();
include_once 'gw-connect.php';
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$createnew = mysqli_real_escape_string($con, $_POST['docreate']);
$userid = $_SESSION['userid'];
echo '<CENTER>Character creation isn\'t enabled yet!<BR />Your userid is ' . $userid . '<BR />';
if ($createnew === "1"){
	$cname = mysqli_real_escape_string($con, $_POST['cname']);
	$bdate = mysqli_real_escape_string($con, $_POST['bdate']);
	$profid = mysqli_real_escape_string($con, $_POST['professionid']);
	list ($y, $m, $d) = explode('-', $bdate);
	if (!checkdate($m, $d, $y)) {
		echo 'Date is invalid ' . $bdate . '<BR />';
		echo 'Date format is YYYY-MM-DD / 2005-04-28<BR />';
		echo 'Please click <A HREF="gw-create.php">HERE</A> to try again';
		echo '<BR /><BR />Return to <A HREF="gw-index.php">home</A>.</CENTER></BODY></HTML>';
		exit();
	}
	$sqlcreate = "INSERT INTO `playername` (charname, birthdate, userid, professionid) VALUES ('$cname', '$bdate', $userid, $profid)";
	echo 'SQL Code w/ variables is: ' . $sqlcreate . '';
	echo 'Character creation database insertion code here';
} else {
	echo 'Form creation code goes here';
	echo '<CENTER><FORM METHOD="POST" ACTION="gw-create.php"><INPUT TYPE="HIDDEN" NAME="docreate" VALUE="1">';
	echo 'Character name: <INPUT TYPE="TEXT" NAME="cname" MAXLENGTH="19" SIZE="20"><BR />';
	echo 'Birthdate: <INPUT NAME="bdate" TYPE="DATE" PLACEHOLDER="2005-04-28"><BR />';
	$sqlprofession = "SELECT * FROM (SELECT * FROM listruneprofessions ORDER BY runeprofid DESC LIMIT 10) sub ORDER BY runeprofid ASC";
	if (!$result = $con->query($sqlprofession)){
		die ('There was an error running the query [' . $con->error . ']');
	}
	echo 'Profession: <SELECT NAME="professionid">';
	echo '<OPTION SELECTED DISABLED>Choose Profession</OPTION>';
	while ($row = $result->fetch_array()){
		$professionid = $row['runeprofid'];
		$profession = $row['runeprofession'];
		echo '<OPTION VALUE="' . $professionid . '">' . $profession . '</OPTION>';
	}
	echo '</SELECT><BR /><BR /> ';
	echo '<INPUT TYPE="SUBMIT" VALUE="Create character ..."></FORM>';
}
?>
<BR /><BR />
Return to <A HREF="gw-index.php">home</A>.
</CENTER>
</BODY>
</HTML>