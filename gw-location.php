<?php
session_start();
require_once 'gw-connect.php';

// 1. Enforce Authentication Check
if (!isset($_SESSION['playerid']) || empty($_SESSION['playerid'])) {
    header('Location: login.php');
    exit;
}

// 2. Database Connection & Error Handling
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if ($con->connect_errno) {
    error_log("Database connection failed: " . $con->connect_error);
    die('A database error occurred. Please try again later.');
}

// 3. Sanitize Session Variables
$profcolor = isset($_SESSION['profcolor']) ? $_SESSION['profcolor'] : '#ffffff';
// Validate $profcolor to ensure it's a valid hex color code
if (!preg_match('/^#[a-fA-F0-9]{6}$/', $profcolor)) {
    $profcolor = '#ffffff';
}

// 4. Query Database
$sqlmaploc = "SELECT treasureid, location FROM treasuredata";
$resultmap = $con->query($sqlmaploc);

if (!$resultmap) {
    error_log("Query failed: " . $con->error);
    die('An error occurred while fetching locations.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="gw-style.css">
    <title>Location Selection</title>
    <style media="screen">
        body { background-color: <?php echo htmlspecialchars($profcolor, ENT_QUOTES, 'UTF-8'); ?>; }
    </style>
</head>
<body>
    <div style="text-align: center;">
        <form method="POST" action="gw-record.php">
            <select name="locationid" onchange="this.form.submit()">
                <option selected disabled>Select a map location</option>
                <?php while ($rowmap = $resultmap->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars((string)$rowmap['treasureid'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($rowmap['location'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <noscript>
                <input type="submit" value="Choose Map Location">
            </noscript>
        </form>
        <br />

        <form method="POST" action="gw-toon.php">
            <input type="hidden" name="playeridid" value="0">
            <input type="submit" value="Return to character selection">
        </form>
        <br /><br />

        <form method="POST" action="gw-logout.php">
            <input type="hidden" name="logout" value="1">
            <input type="submit" value="Logout">
        </form>
    </div>
</body>
</html>
<?php
$resultmap->close();
$con->close();
?>
