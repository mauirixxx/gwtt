<?php
/**
 * Shared navigation header for Guild Wars Treasure Tracker.
 *
 * Include this file after session_start(). It intentionally contains no
 * database access so it can be reused throughout the application.
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$gwNavLoggedIn = !empty($_SESSION['userid']);
$gwNavAccess = (int)($_SESSION['access'] ?? 0);
$gwNavUsername = (string)($_SESSION['username'] ?? 'User');
?>
<header class="gw-site-header">
    <div class="gw-site-header__inner">
        <a class="gw-site-header__brand" href="gw-index.php">Guild Wars Treasure Tracker</a>

        <nav class="gw-site-header__nav" aria-label="Main navigation">
            <a href="gw-index.php">Home</a>
            <?php if ($gwNavLoggedIn): ?>
                <a href="gw-toon.php">Characters</a>
                <a href="gw-create.php">Add Character</a>
                <?php if ($gwNavAccess === 9): ?>
                    <a href="gw-admin.php">Admin</a>
                <?php endif; ?>
            <?php endif; ?>
        </nav>

        <?php if ($gwNavLoggedIn): ?>
            <div class="gw-site-header__user">
                <form method="POST" action="gw-logout.php" onsubmit="return confirm('Sign out of Guild Wars Treasure Tracker?');">
                    <?php echo gw_csrf_input(); ?>
                    <input type="hidden" name="logout" value="1">
                    <span>Signed in as </span><button type="submit" class="gw-site-header__username"><?php echo htmlspecialchars($gwNavUsername, ENT_QUOTES, 'UTF-8'); ?></button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</header>

<style>
.gw-site-header {
    background: #20252b;
    border-bottom: 3px solid #b58a45;
    color: #fff;
    font-family: Arial, Helvetica, sans-serif;
    margin: 0 0 28px;
}
.gw-site-header__inner {
    align-items: center;
    display: flex;
    gap: 24px;
    margin: 0 auto;
    max-width: 1100px;
    min-height: 64px;
    padding: 0 20px;
}
.gw-site-header__brand {
    color: #f0d39a;
    font-size: 1.15rem;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
}
.gw-site-header__nav {
    display: flex;
    flex: 1;
    flex-wrap: wrap;
    gap: 6px;
}
.gw-site-header__nav a {
    border-radius: 4px;
    color: #fff;
    padding: 9px 11px;
    text-decoration: none;
}
.gw-site-header__nav a:hover,
.gw-site-header__nav a:focus {
    background: #343b44;
    color: #f0d39a;
}
.gw-site-header__user {
    color: #d7d7d7;
    font-size: .9rem;
    white-space: nowrap;
}
.gw-site-header__user form { margin: 0; }
.gw-site-header__username {
    background: none;
    border: 0;
    color: #f0d39a;
    cursor: pointer;
    font: inherit;
    font-weight: 700;
    padding: 0;
    text-decoration: underline;
}
.gw-site-header__username:hover,
.gw-site-header__username:focus { color: #fff; }
@media (max-width: 760px) {
    .gw-site-header__inner {
        align-items: flex-start;
        flex-direction: column;
        gap: 8px;
        padding-bottom: 14px;
        padding-top: 14px;
    }
    .gw-site-header__nav {
        width: 100%;
    }
    .gw-site-header__user {
        white-space: normal;
    }
}
</style>
