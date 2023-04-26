<?php
    require_once(__DIR__.'/../Model/user_sql.php');
    require_once(__DIR__.'/parse.php');
    
    if (session_status() === PHP_SESSION_NONE)
        session_start();

    if (!isset($_SESSION['logged']) || !isset($_SESSION['token']) || !isset($_SESSION['name']) || !isset($_SESSION['id']) || !is_numeric($_SESSION['id']) || (isset($_SESSION['id']) && !check_user_token($_SESSION['id'], string_parse($_SESSION['token']))))
        require(__DIR__."/logout.php");
    $logged_user_id = $_SESSION['id'];
    $logged_user_username = string_parse($_SESSION['name']);
?>