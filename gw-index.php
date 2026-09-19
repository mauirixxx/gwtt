<?php
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['userid']) && !empty($_SESSION['userid']);
$username   = $isLoggedIn ? ($_SESSION['username'] ?? 'User') : '';
$accessLevel = $isLoggedIn ? (int)($_SESSION['access'] ?? 0) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="gw-style.css">
    <title><?php echo $isLoggedIn ? 'Welcome, ' . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') : 'Login Required'; ?></title>
</head>
<body>
<div style="text-align: center; margin-top: 50px;">

<?php if ($isLoggedIn): ?>

    <h2>Welcome, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>!</h2>
    <p>Proceed to character selection <a href="gw-toon.php" class="navlink">here</a>.</p>
    <p>Create a new character to record <a href="gw-create.php" class="navlink">here</a>.</p>

    <?php if ($accessLevel === 9): ?>
        <br />
        <p>Hello Admin, please click <a href="gw-admin.php" class="navlink">here</a> to access the admin page.</p>
    <?php endif; ?>

    <br /><br />
    <form method="POST" action="gw-logout.php">
        <input type="hidden" name="logout" value="1">
        <input type="submit" value="Logout">
    </form>

<?php else: ?>

    <h2>Login Required</h2>
    <form action="gw-login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" size="20" required><br /><br />
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" size="20" required><br /><br />
        
        <input type="submit" value="Login ...">
    </form>

<?php endif; ?>

</div>
</body>
</html>
