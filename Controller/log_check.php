<?php
    require_once(__DIR__.'/../Model/user_sql.php');
    if (session_status() === PHP_SESSION_NONE)
        session_start();

    if (!isset($_SESSION['logged']) || !isset($_SESSION['id']) || !find_user(array('user_id'), 'user_id', $_SESSION['id']))
        require(__DIR__."/logout.php");
?>