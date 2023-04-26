<?php
    require_once(__DIR__."/../Controller/log_public_check.php");
?>
<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="shortcut icon" href="#" />
</head>
<html>
    <div class="header">
        <a href="/View/home.php" class="header-logo"> Camagru </a>
        <div class="header-links">
            <a href="/View/gallery.php"> Gallery </a>
            <?php if ($user_is_log): ?>
                <a href="/View/profile.php?id=<?=$logged_user_id?>"> Profile </a>
                <a href="/Controller/logout.php"> Logout </a>
            <?php else: ?>
                <a href="/View/login.php"> Sign in </a>
                <a href="/View/register.php"> Sign up </a>
            <?php endif; ?>
        </div>
    </div>
</html>
