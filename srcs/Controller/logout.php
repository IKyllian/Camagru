<?php
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    session_destroy();
    header("Location: /View/login.php");
    exit;
?>