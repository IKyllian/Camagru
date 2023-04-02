<?php
    require_once(__DIR__.'/../Model/user_sql.php');
    session_start();
    if (!isset($_SESSION['logged']) || !isset($_SESSION['id']) || !find_user(array('user_id'), 'user_id', $_SESSION['id']))
        redirect_to("/View/login.php");
?>