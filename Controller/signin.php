<?php

require_once("../Model/user_sql.php");
require_once(__DIR__ . "/redirect.php");
session_start();

function validate_data($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function is_valid_datas($username, $password) {
    if (!isset($username, $password)) {
        redirect_to("/View/login.php", "error_msg", "Please complete the form");
    }

    if (empty($username) || empty($password)) {
        redirect_to("/View/login.php", "error_msg", "Please complete the form");
    }

    //Check password and username

    return (1);
}

if (is_valid_datas($_POST['username'], $_POST['password'])) {
    $username = validate_data($_POST['username']);
    $password = validate_data($_POST['password']);
    
    user_signin($username, $password);
}

?>