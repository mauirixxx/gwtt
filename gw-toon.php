<?php
session_start();
require_once 'gw-connect.php';

// 1. Strict Authentication Check
if (!isset($_SESSION['userid']) \vert{}\vert{} empty($_SESSION['userid'])) {
    header('Location: gw-login.php');
    exit;
}

// 2. Database Connection
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if ($con->connect_errno) {
    error_log("Database error: " . $con->connect_error);
    die("A database error occurred.");
}

$userid = (int)$_SESSION['userid'];$whattoon = isset($_POST['playerid']) ? (int)$_POST['playerid'] : 0;

$charactername = '';$profcolor = '#ffffff';
$selectedToonId = 0;

// 3. Process Character Selection with IDOR Protection
if ($whattoon > 0) {
    // Verify that the requested character actually belongs to the logged-in user
    $stmtToon =$con->prepare("SELECT playerid, charname, profcolor FROM `playername` WHERE `playerid` = ? AND `userid` = ?");
    $stmtToon->bind_param("ii", $whattoon, $userid);$stmtToon->execute();
    $resToon =$stmtToon->get_result();

    if ($rowToon =$resToon->fetch_assoc()) {
        $selectedToonId = (int)$rowToon['playerid'];
        $charactername  =$rowToon['charname'];
        $profcolor      =$rowToon['profcolor'];

        // Validate hex color format
        if (!preg_match('/^#[a-fA-F0-9]{6}$/', $profcolor)) {$profcolor = '#ffffff';
        }

        // Set session state safely
        $_SESSION['playerid']  =$selectedToonId;
        $_SESSION['profcolor'] =$profcolor;
    }
    $stmtToon->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="gw-style.css">
    <title><?php echo $selectedToonId > 0 ? htmlspecialchars($charactername, ENT_QUOTES, 'UTF-8') : 'Character Selection'; ?></title>
    <style media="screen">
        body { background-color: <?php echo htmlspecialchars($profcolor, ENT_QUOTES, 'UTF-8'); ?>; }
    </style>
</head>
<body>

<div style="text-align: center;">

<?php if ($selectedToonId === 0): ?>
    <!-- CHARACTER SELECTION FORM -->
    <form method="POST">
        <select name="playerid" onchange="this.form.submit()">
            <option selected disabled>Select a Character</option>
            <?php
            $stmtList =$con->prepare("SELECT playerid, charname FROM `playername` WHERE `userid` = ? ORDER BY `charname` ASC");
            $stmtList->bind_param("i", $userid);$stmtList->execute();
            $resList =$stmtList->get_result();

            while ($row =$resList->fetch_assoc()) {
                echo '<option value="' . (int)$row['playerid'] . '">' . htmlspecialchars($row['charname'], ENT_QUOTES, 'UTF-8') . '</option>';
            }
            $stmtList->close();
            ?>
        </select>
        <noscript><input type="submit" value="Choose Toon"></noscript>
    </form>
    <br /><br />
    <form action="gw-create.php" method="GET">
        <input type="submit" value="Add a toon">
    </form>

<?php else: ?>
    <!-- ACTION SELECTION FORM -->
    <form method="POST" action="gw-action.php">
        <fieldset class="radiogroup">
            <legend>Select your course of action</legend>
            <ul class="radio" style="list-style: none; padding: 0;">
                <li style="text-align: center;">
                    <label><input type="radio" name="gwaction" value="1" required> Record loot info</label>
                </li>
                <li style="text-align: center;">
                    <label><input type="radio" name="gwaction" value="2"> View Character loot history</label>
                </li>
            </ul>
        </fieldset>
        <br />
        <input type="submit" value="Choose action">
    </form>
    <br /><br />
    <form method="POST" action="gw-toon.php">
        <input type="submit" value="Return to character selection">
    </form>

<?php endif; ?>

    <br /><br />
    <form method="POST" action="gw-logout.php">
        <input type="hidden" name="logout" value="1">
        <input type="submit" value="Logout">
    </form>
</div>

</body>
</html>
<?php $con->close(); ?>
