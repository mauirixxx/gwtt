<?php
session_start();

// 1. Strict Authentication & Character Context Check
if (!isset($_SESSION['userid']) || !isset($_SESSION['playerid']) || empty($_SESSION['playerid'])) {
    header('Location: gw-toon.php');
    exit;
}

// 2. Sanitize and validate inputs
$playerid = (int)$_SESSION['playerid'];
$action   = isset($_POST['gwaction']) ? (int)$_POST['gwaction'] : 0;

$targetUrl = '';
$inputName = '';

if ($action === 1) {
    $targetUrl = 'gw-location.php';
    $inputName = 'playerid';
} elseif ($action === 2) {
    $targetUrl = 'gw-history.php';
    $inputName = 'cnameid';
} else {
    header('Location: gw-toon.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="gw-style.css">
    <title>Redirecting...</title>
</head>
<body onload="document.forms['redirectForm'].submit();">
    <div style="text-align: center; margin-top: 50px;">
        <form method="POST" action="<?php echo htmlspecialchars($targetUrl, ENT_QUOTES, 'UTF-8'); ?>" name="redirectForm">
            <input type="hidden" name="<?php echo htmlspecialchars($inputName, ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo $playerid; ?>">
            <p>Redirecting to destination...</p>
            <noscript>
                <p>JavaScript is disabled. Click the button below to continue:</p>
                <input type="submit" value="Continue">
            </noscript>
        </form>
    </div>
</body>
</html>
