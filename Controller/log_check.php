<?php
    require_once(__DIR__.'/../Model/user_sql.php');
    if (session_status() === PHP_SESSION_NONE)
        session_start();

    if (!isset($_SESSION['logged']) || !isset($_SESSION['token']) || !isset($_SESSION['id']) || !check_user_token($_SESSION['id'], $_SESSION['token']))
        require(__DIR__."/logout.php");
?>