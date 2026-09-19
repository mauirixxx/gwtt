<?php
session_start();
require_once 'gw-connect.php';

// Prevent processing if not a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: gw-index.php');
    exit;
}

// 1. Establish Database Connection
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if ($con->connect_errno) {
    error_log("Database connection failed: " . $con->connect_error);
    die("A database error occurred. Please try again later.");
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$loginSuccess = false;
$errorMessage = '';

if (!empty($username) && !empty($password)) {
    // 2. Query User via Prepared Statement
    $stmt = $con->prepare("SELECT userid, username, password, access FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // 3. Verify Password (Supports modern password_hash() OR legacy MD5 for migration)
        $hashedPasswordInDb = $row['password'];

        if (password_verify($password, $hashedPasswordInDb) || $hashedPasswordInDb === md5($password)) {
            // Re-hash legacy MD5 passwords to bcrypt/argon2 automatically upon login
            if ($hashedPasswordInDb === md5($password)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $updateStmt = $con->prepare("UPDATE users SET password = ? WHERE userid = ?");
                $updateStmt->bind_param("si", $newHash, $row['userid']);
                $updateStmt->execute();
                $updateStmt->close();
            }

            // 4. Prevent Session Fixation
            session_regenerate_id(true);

            // Set session variables
            $_SESSION['username'] = $row['username'];
            $_SESSION['userid']   = (int)$row['userid'];
            $_SESSION['access']   = $row['access'];

            $loginSuccess = true;
        }
    }
    $stmt->close();
}

$con->close();

if ($loginSuccess) {
    header('Location: gw-index.php');
    exit;
} else {
    http_response_code(401);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="gw-style.css">
    <title>Invalid Login</title>
</head>
<body>
    <div style="text-align: center; margin-top: 50px;">
        <h2>Invalid Login</h2>
        <p>That was not a valid username or password.</p>
        <p>Please try again <a href="gw-index.php" class="navlink">here</a>.</p>
    </div>
</body>
</html>
