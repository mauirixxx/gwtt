<?php
session_start();

// Process logout logic if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    // 1. Unset all session variables
    $_SESSION = array();

    // 2. Clear the session cookie on the client side
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // 3. Destroy the server-side session
    session_destroy();

    // 4. Clean HTTP redirect back to index/login page
    header('Location: gw-index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="gw-style.css">
    <title>Logging Out</title>
</head>
<body>
    <div style="text-align: center; margin-top: 50px;">
        <p>Something went wrong, or no logout request was received.</p>
        <form method="POST" action="gw-logout.php">
            <input type="hidden" name="logout" value="1">
            <input type="submit" value="Click here to Logout">
        </form>
        <br />
        <a href="gw-index.php" class="navlink">Return to Home</a>
    </div>
</body>
</html>
