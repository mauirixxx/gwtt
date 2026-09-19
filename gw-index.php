<?php
session_start();
require_once 'gw-security.php';

$isLoggedIn = !empty($_SESSION['userid']);
$username = $isLoggedIn ? ($_SESSION['username'] ?? 'User') : '';
$accessLevel = $isLoggedIn ? (int)($_SESSION['access'] ?? 0) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="gw-style.css">
    <title><?php echo $isLoggedIn ? 'Welcome, ' . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') : 'Login Required'; ?></title>
<style>
.login-wrap{max-width:520px;margin:35px auto;padding:0 20px}.login-card{background:rgba(255,255,255,.78);border:1px solid #bbb;border-radius:6px;padding:24px 28px;box-shadow:0 2px 8px rgba(0,0,0,.08)}.login-card h2{text-align:center;margin:0 0 22px}.login-field{display:grid;grid-template-columns:110px 1fr;gap:14px;align-items:center;margin-bottom:16px}.login-field label{float:none;width:auto;margin:0;padding:0;text-align:right}.login-field input{box-sizing:border-box;font:inherit;padding:7px 8px;width:100%}.login-actions{text-align:center;margin-top:22px}.login-actions input{font-size:1rem;padding:8px 16px}.login-register{text-align:center;margin-top:22px}@media(max-width:560px){.login-field{grid-template-columns:1fr;gap:6px}.login-field label{text-align:left}.login-card{padding:20px}}
</style></head>
<body>
<?php require 'gw-header.php'; ?>

<?php if ($isLoggedIn): ?>
<div style="text-align: center; margin-top: 50px;">
    <h2>Welcome, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>!</h2>
    <p>Proceed to character selection <a href="gw-toon.php" class="navlink">here</a>.</p>
    <p>Create a new character to record <a href="gw-create.php" class="navlink">here</a>.</p>
    <?php if ($accessLevel === 9): ?>
        <br><p>Hello Admin, please click <a href="gw-admin.php" class="navlink">here</a> to access the admin page.</p>
    <?php endif; ?>
    <br><br>
    <form method="POST" action="gw-logout.php">
        <?php echo gw_csrf_input(); ?>
        <input type="hidden" name="logout" value="1">
        <input type="submit" value="Logout">
    </form>
</div>
<?php else: ?>
<main class="login-wrap"><section class="login-card"><h2>Login Required</h2>
<form action="gw-login.php" method="POST"><?php echo gw_csrf_input(); ?>
<div class="login-field"><label for="username">Username:</label><input type="text" id="username" name="username" maxlength="50" autocomplete="username" required autofocus></div>
<div class="login-field"><label for="password">Password:</label><input type="password" id="password" name="password" autocomplete="current-password" required></div>
<div class="login-actions"><input type="submit" value="Sign in"></div></form>
<div class="login-register">Don't have an account? <a href="gw-register.php" class="navlink">Create an account</a></div>
</section></main>
<?php endif; ?>
</body>
</html>
