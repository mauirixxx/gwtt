<?php
session_start();
require_once 'gw-connect.php';

// 1. Authentication Check
if (!isset($_SESSION['playerid']) || empty($_SESSION['playerid'])) {
    header('Location: login.php');
    exit;
}

// 2. Database Connection
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if ($con->connect_errno) {
    error_log("Database connection failed: " . $con->connect_error);
    die("Database connection failed.");
}

// 3. Input & Session Sanitization
$cnameid = (int)$_SESSION['playerid'];

$profcolor = $_SESSION['profcolor'] ?? '#ffffff';
if (!preg_match('/^#[a-fA-F0-9]{6}$/', $profcolor)) {
    $profcolor = '#ffffff';
}

// 4. Single Master Query with LEFT JOINs (Eliminates N+1 queries & SQLi)
$sql = "SELECT 
            h.historydate, 
            h.goldrec, 
            h.itemtype, 
            h.itemreq, 
            h.itemname, 
            p.charname, 
            t.location, 
            t.wikilink,
            lr.runes AS runename,
            lrat.rarity AS rarityname,
            la.weaponattribute AS attrname,
            lt.weapontype AS weapname,
            m.material AS matname
        FROM history h
        INNER JOIN treasuredata t ON h.locationid = t.treasureid
        INNER JOIN playername p ON h.charnameid = p.playerid
        LEFT JOIN listrunes lr ON h.runetype = lr.runeid
        LEFT JOIN listrarity lrat ON h.itemrarity = lrat.rareid
        LEFT JOIN listattribute la ON h.itemattribute = la.weapattrid
        LEFT JOIN listtype lt ON h.itemtype = lt.weaponid
        LEFT JOIN materials m ON h.material = m.materialid
        WHERE h.charnameid = ?
        ORDER BY h.historydate ASC, h.historyid ASC";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $cnameid);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="gw-style.css">
    <title>Treasure Data</title>
    <style media="screen">
        body { background-color: <?php echo htmlspecialchars($profcolor, ENT_QUOTES, 'UTF-8'); ?>; }
    </style>
</head>
<body>

<div style="text-align: center;">
<?php if ($result->num_rows > 0): ?>
    <table style="margin: 0 auto; border: 0;">
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    On <?php echo htmlspecialchars($row['historydate'], ENT_QUOTES, 'UTF-8'); ?>, 
                    "<?php echo htmlspecialchars($row['charname'], ENT_QUOTES, 'UTF-8'); ?>" got 
                    <?php echo (int)$row['goldrec']; ?>GP and 

                    <?php if ((int)$row['itemtype'] === 16): ?>
                        a rune of <?php echo htmlspecialchars($row['runename'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?>

                    <?php elseif ((int)$row['itemtype'] === 17): ?>
                        nothing dropped at this location on this date

                    <?php elseif (!empty($row['matname'])): ?>
                        a <?php echo htmlspecialchars($row['matname'], ENT_QUOTES, 'UTF-8'); ?>

                    <?php else: ?>
                        a <?php echo htmlspecialchars($row['rarityname'] ?? '', ENT_QUOTES, 'UTF-8'); ?> 
                        r<?php echo (int)$row['itemreq']; ?> 
                        <?php echo htmlspecialchars($row['attrname'] ?? '', ENT_QUOTES, 'UTF-8'); ?> 
                        <?php echo htmlspecialchars($row['weapname'] ?? '', ENT_QUOTES, 'UTF-8'); ?> 
                        named <?php echo htmlspecialchars($row['itemname'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    <?php endif; ?>

                    at <a href="<?php echo htmlspecialchars($row['wikilink'], ENT_QUOTES, 'UTF-8'); ?>" class="navlink">
                        <?php echo htmlspecialchars($row['location'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>There is no data to display for that character yet.</p>
<?php endif; ?>
</div>

<br />
<div style="text-align: center;">
    <form method="POST" action="gw-toon.php">
        <input type="hidden" name="cnameid" value="0">
        <input type="submit" value="Return to character selection">
    </form>
</div>

</body>
</html>
<?php
$stmt->close();
$con->close();
?>
