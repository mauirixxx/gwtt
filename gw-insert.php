<?php
session_start();
require_once 'gw-connect.php';

// 1. Authentication Check
if (!isset($_SESSION['playerid']) \vert{}\vert{} !isset($_SESSION['userid'])) {
    http_response_code(403);
    die('Unauthorized access.');
}

// 2. Database Connection
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if ($con->connect_errno) {
    error_log("Database connection failed: " . $con->connect_error);
    die("A database error occurred.");
}

// 3. Extract & Cast Core Inputs
$uid       = (int)$_SESSION['userid'];$toonid    = (int)$_SESSION['playerid'];$gold      = isset($_POST['droppedgold']) ? (int)$_POST['droppedgold'] : 0;
$droptype  = isset($_POST['droptype']) ? (int)$_POST['droptype'] : 0;
$locid     = isset($_POST['location']) ? (int)$_POST['location'] : 0;
$treasdate = isset($_POST['treasuredate']) ?$_POST['treasuredate'] : date('Y-m-d');

// Basic date format validation (YYYY-MM-DD)
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $treasdate)) {$treasdate = date('Y-m-d');
}

// 4. Handle Drops via Prepared Statements
if ($droptype === 1) {
    // Weapon Drop
    $rarity = (int)$_POST['rare'];
    $req    = (int)$_POST['requirement'];
    $attrib = (int)$_POST['attribute'];
    $weap   = (int)$_POST['weapon'];
    $itname = trim($_POST['itemname'] ?? '');

    $stmt =$con->prepare("INSERT INTO `history` (historydate, userid, charnameid, locationid, goldrec, itemreq, itemtype, itemattribute, itemrarity, itemname) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiiiiiis", $treasdate,$uid, $toonid,$locid, $gold,$req, $weap,$attrib, $rarity,$itname);
    
} elseif ($droptype === 2) {
    // Rare Material Drop
    $matid = (int)$_POST['rarematerial'];

    $stmt =$con->prepare("INSERT INTO `history` (historydate, userid, charnameid, locationid, goldrec, material) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiii", $treasdate,$uid, $toonid,$locid, $gold,$matid);

} elseif ($droptype === 3) {     // Rune Drop$runeid   = (int)$_POST['rune'];$runerare = (int)$_POST['runerarity'];$itemtype = 16;

    $stmt =$con->prepare("INSERT INTO `history` (historydate, userid, charnameid, locationid, goldrec, itemtype, itemrarity, runetype) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiiiii", $treasdate,$uid, $toonid,$locid, $gold,$itemtype, $runerare,$runeid);

} elseif ($droptype === 4) {     // Nothing Dropped$itname     = trim($_POST['itemname'] ?? 'Nothing dropped!');$itnothing  = isset($_POST['itemtype']) ? (int)$_POST['itemtype'] : 17;

    $stmt =$con->prepare("INSERT INTO `history` (historydate, userid, charnameid, locationid, goldrec, itemtype, itemname) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiiis", $treasdate, $uid,$toonid, $locid,$gold, $itnothing,$itname);

} else {
    die("Invalid drop type provided.");
}

// 5. Execute Query
if (!$stmt->execute()) {
    error_log("Insert failed: " . $stmt->error);
    die("Failed to record drop data.");
}

$stmt->close();$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="gw-style.css">
    <title>Inserting Data...</title>
</head>
<body onload="document.forms['returntotoons'].submit();">
    <div style="text-align: center;">
        <p>Record saved successfully. Redirecting...</p>
        <form method="POST" action="gw-toon.php" name="returntotoons">
            <input type="submit" value="Continue">
        </form>
    </div>
</body>
</html>
