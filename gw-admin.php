<?php
session_start();

// 1. Check Authentication
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header('Location: gw-login.php');
    exit;
}

// 2. Strict Privilege Enforcement (Admin Access Check)
$accessLevel = (int)($_SESSION['access'] ?? 0);
if ($accessLevel !== 9) {
    http_response_code(403);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="gw-style.css">
        <title>Access Denied</title>
    </head>
    <body>
        <div style="text-align: center; margin-top: 50px;">
            <h2>Access Denied</h2>
            <p>You do not have permission to access administrator tools.</p>
            <p>Click <a href="gw-index.php" class="navlink">HERE</a> to return to the home page.</p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$username = $_SESSION['username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="gw-style.css">
    <title>Admin Dashboard - Welcome, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>
<div style="text-align: center; margin-top: 50px;">

    <h2>Administrator Tools</h2>
    
    <div style="margin-bottom: 20px;">
        <p><strong>Delete a Character:</strong> <a href="gw-deletetoon.php" class="navlink">here</a></p>
        <p><small>Deletes a character from a user account and all recorded drop data. (Irreversible)</small></p>
    </div>

    <div style="margin-bottom: 20px;">
        <p><strong>Delete a User:</strong> <a href="gw-deleteuser.php" class="navlink">here</a></p>
        <p><small>Deletes a user, all associated characters, and drop data. (Irreversible)</small></p>
    </div>

    <br />
    <p>Click <a href="gw-index.php" class="navlink">HERE</a> to return to the home page.</p>

    <br /><br />
    <form method="POST" action="gw-logout.php">
        <input type="hidden" name="logout" value="1">
        <input type="submit" value="Logout">
    </form>

</div>
</body>
</html>
