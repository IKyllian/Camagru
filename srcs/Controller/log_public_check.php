<?php
    require_once(__DIR__.'/../Model/user_sql.php');
    require_once(__DIR__.'/parse.php');
    
    if (session_status() === PHP_SESSION_NONE)
        session_start();

    $user_is_log;
    $logged_user_id;
    $logged_user_username;

    if (!isset($_SESSION['logged']) || !isset($_SESSION['token']) || !isset($_SESSION['name']) || !isset($_SESSION['id']) || !is_numeric($_SESSION['id']))
        $user_is_log = FALSE;
    else if ((isset($_SESSION['id']) && check_user_token($_SESSION['id'], string_parse($_SESSION['token'])))) {
        $user_is_log = TRUE;
        $logged_user_id = $_SESSION['id'];
        $logged_user_username = string_parse($_SESSION['name']);
    } else {
        session_destroy();
        $user_is_log = FALSE;
    }
?>