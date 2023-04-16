<?php
    require_once(__DIR__."/../Model/user_sql.php");
    require_once(__DIR__."/redirect.php");
    require_once(__DIR__."/parse.php");
    session_start();

    if (!is_datas_set($_POST, array('username', 'password'))) {
        redirect_to("/View/login.php", "error_msg", "Please complete the form");
    } 

    $username = string_parse($_POST['username']);
    $password = string_parse($_POST['password']);

    user_signin($username, $password);

?>