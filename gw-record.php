<?php
session_start();
require_once 'gw-connect.php';

// 1. Auth Check
if (!isset($_SESSION['playerid']) \vert{}\vert{} empty($_SESSION['playerid'])) {
    header('Location: login.php');
    exit;
}

// 2. Database Connection
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if ($con->connect_errno) {
    error_log("Database error: " . $con->connect_error);
    die("Database connection failed.");
}

// 3. Inputs & Validation
$toonid    = (int)$_SESSION['playerid'];$location  = isset($_POST['locationid']) ? (int)$_POST['locationid'] : 0;
$whatdropped = isset($_POST['gwdrop']) ? (int)$_POST['gwdrop'] : 0;

$profcolor = isset($_SESSION['profcolor']) ?$_SESSION['profcolor'] : '#ffffff';
if (!preg_match('/^#[a-fA-F0-9]{6}$/', $profcolor)) {$profcolor = '#ffffff';
}

// 4. Prepared Statement for Location Query
$locname = '';
$loclink = '';$locid   = 0;

if ($location > 0) {
    $stmt =$con->prepare("SELECT treasureid, location, wikilink FROM `treasuredata` WHERE `treasureid` = ?");
    $stmt->bind_param("i", $location);$stmt->execute();
    $result =$stmt->get_result();
    
    if ($row =$result->fetch_assoc()) {
        $locid   = (int)$row['treasureid'];
        $locname =$row['location'];
        $loclink =$row['wikilink'];
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="gw-style.css">
    <title>What Dropped?</title>
    <style media="screen">
        body { background-color: <?php echo htmlspecialchars($profcolor, ENT_QUOTES, 'UTF-8'); ?>; }
    </style>
</head>
<body>

<div style="text-align: center;">
<?php if ($locid > 0): ?>
    At <a href="<?php echo htmlspecialchars($loclink, ENT_QUOTES, 'UTF-8'); ?>" class="navlink">
        <?php echo htmlspecialchars($locname, ENT_QUOTES, 'UTF-8'); ?>
    </a> (Guild Wars Wiki link)
<?php else: ?>
    <p>Invalid location selected.</p>
<?php endif; ?>
</div>

<br />

<?php if ($whatdropped === 1): ?>
    <!-- WEAPON FORM -->
    <div style="text-align: center;">
        <form method="POST" action="gw-insert.php">
            on <input name="treasuredate" type="date" value="<?php echo date('Y-m-d'); ?>"> a 
            
            <select name="rare">
                <?php
                $res =$con->query("SELECT rareid, rarity FROM `listrarity` ORDER BY `rareid` ASC");
                while ($row =$res->fetch_assoc()) {
                    echo '<option value="' . (int)$row['rareid'] . '">' . htmlspecialchars($row['rarity'], ENT_QUOTES, 'UTF-8') . '</option>';
                }
                ?>
            </select>, 

            req<select name="requirement">
                <?php
                $res =$con->query("SELECT req FROM `listreq` ORDER BY `req` ASC");
                while ($row =$res->fetch_assoc()) {
                    echo '<option value="' . (int)$row['req'] . '">' . (int)$row['req'] . '</option>';
                }
                ?>
            </select>

            <select name="attribute">
                <?php
                $res =$con->query("SELECT weapattrid, weaponattribute FROM `listattribute` ORDER BY `weapattrid` ASC");
                while ($row =$res->fetch_assoc()) {
                    echo '<option value="' . (int)$row['weapattrid'] . '">' . htmlspecialchars($row['weaponattribute'], ENT_QUOTES, 'UTF-8') . '</option>';
                }
                ?>
            </select>

            <select name="weapon">
                <?php
                $res =$con->query("SELECT weaponid, weapontype FROM `listtype` ORDER BY `weaponid` ASC");
                while ($row =$res->fetch_assoc()) {
                    echo '<option value="' . (int)$row['weaponid'] . '">' . htmlspecialchars($row['weapontype'], ENT_QUOTES, 'UTF-8') . '</option>';
                }
                ?>
            </select> 
            called the <input type="text" name="itemname" maxlength="100" size="40">
            and <input type="number" name="droppedgold" min="1" max="9999"> gold pieces.
            
            <input type="hidden" name="droptype" value="1">
            <input type="hidden" name="location" value="<?php echo $locid; ?>">
            <input type="hidden" name="chartoon" value="<?php echo $toonid; ?>">
            <br /><input type="submit" value="Submit Drop">
        </form>
    </div>

<?php elseif ($whatdropped === 2): ?>
    <!-- RARE MATERIAL FORM -->
    <div style="text-align: center;">
        <form method="POST" action="gw-insert.php">
            on <input name="treasuredate" type="date" value="<?php echo date('Y-m-d'); ?>"> a 
            <select name="rarematerial">
                <?php
                $res =$con->query("SELECT materialid, material FROM `materials` ORDER BY `materialid` ASC");
                while ($row =$res->fetch_assoc()) {
                    echo '<option value="' . (int)$row['materialid'] . '">' . htmlspecialchars($row['material'], ENT_QUOTES, 'UTF-8') . '</option>';
                }
                ?>
            </select> 
            and <input type="number" name="droppedgold" min="1" max="9999"> gold pieces.
            <input type="hidden" name="droptype" value="2">
            <input type="hidden" name="location" value="<?php echo $locid; ?>">
            <input type="hidden" name="chartoon" value="<?php echo $toonid; ?>">
            <br /><input type="submit" value="Submit Drop">
        </form>
    </div>

<?php elseif ($whatdropped === 3): ?>
    <!-- RUNE FORM -->
    <div style="text-align: center;">
        <form method="POST" action="gw-insert.php">
            on <input name="treasuredate" type="date" value="<?php echo date('Y-m-d'); ?>"> a 
            <select name="runerarity">
                <option value="2">Blue</option>
                <option value="3">Purple</option>
                <option value="4">Gold</option>
            </select> 
            rune of 
            <select name="rune">
                <?php
                $res =$con->query("SELECT runeid, runes FROM `listrunes` ORDER BY `runeid` ASC");
                while ($row =$res->fetch_assoc()) {
                    echo '<option value="' . (int)$row['runeid'] . '">' . htmlspecialchars($row['runes'], ENT_QUOTES, 'UTF-8') . '</option>';
                }
                ?>
            </select> 
            and <input type="number" name="droppedgold" min="0" max="9999"> gold pieces.
            <input type="hidden" name="droptype" value="3">
            <input type="hidden" name="location" value="<?php echo $locid; ?>">
            <input type="hidden" name="chartoon" value="<?php echo $toonid; ?>">
            <br /><input type="submit" value="Submit Drop">
        </form>
    </div>

<?php elseif ($whatdropped === 4): ?>
    <!-- NOTHING DROPPED FORM -->
    <div style="text-align: center;">
        <form method="POST" action="gw-insert.php">
            on <input name="treasuredate" type="date" value="<?php echo date('Y-m-d'); ?>"> nothing dropped!
            <input type="hidden" name="droppedgold" value="0">
            <input type="hidden" name="itemname" value="Nothing dropped!">
            <input type="hidden" name="droptype" value="4">
            <input type="hidden" name="location" value="<?php echo $locid; ?>">
            <input type="hidden" name="itemtype" value="17">
            <input type="hidden" name="chartoon" value="<?php echo $toonid; ?>">
            <br /><input type="submit" value="Submit Drop">
        </form>
    </div>

<?php else: ?>
    <!-- SELECT DROP TYPE -->
    <div style="text-align: center;">
        <form method="POST">
            <select name="gwdrop" onchange="this.form.submit()">
                <option selected disabled>Choose drop type</option>
                <option value="1">Weapon</option>
                <option value="2">Rare Material</option>
                <option value="3">Rune</option>
                <option value="4">Nothing!</option>
            </select>
            <input type="hidden" name="locationid" value="<?php echo $location; ?>">
            <noscript><input type="submit" value="Submit"></noscript>
        </form>
    </div>
<?php endif; ?>

<br />
<div style="text-align: center;">
    <form method="POST" action="gw-location.php">
        <input type="submit" value="Return to location selection">
    </form>
    <br /><br />
    <form method="POST" action="gw-logout.php">
        <input type="hidden" name="logout" value="1">
        <input type="submit" value="Logout">
    </form>
</div>

</body>
</html>
<?php $con->close(); ?>
