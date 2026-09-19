<?php
session_start();

// 1. Strict Session / Auth Check
if (!isset($_SESSION['playerid']) || empty($_SESSION['playerid'])) {
    http_response_code(403);
    die('Unauthorized access.');
}

// 2. Sanitize and validate inputs
$playerid = htmlspecialchars((string)$_SESSION['playerid'], ENT_QUOTES, 'UTF-8');
$action = isset($_POST['gwaction']) ? (int)$_POST['gwaction'] : 0;

$targetUrl = '';
$inputName = '';

if ($action === 1) {
    $targetUrl = 'gw-location.php';
    $inputName = 'playerid';
} elseif ($action === 2) {
    $targetUrl = 'gw-history.php';
    $inputName = 'cnameid';
} else {
    http_response_code(400);
    die('Invalid action provided.');
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
    <form method="POST" action="<?php echo $targetUrl; ?>" name="redirectForm">
        <input type="hidden" name="<?php echo $inputName; ?>" value="<?php echo $playerid; ?>">
        <noscript>
            <p>JavaScript is disabled. Click button to continue:</p>
            <input type="submit" value="Continue">
        </noscript>
    </form>
</body>
</html>
