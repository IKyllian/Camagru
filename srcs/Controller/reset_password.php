<?php
    require_once(__DIR__."/../Model/user_sql.php");
    require_once(__DIR__."/parse.php");
    session_regenerate_id();

    if (!is_datas_set($_POST, array('email', 'reset_code'))) {
        redirect_to("/View/login.php", "error_msg", "Please complete the form");
    }

    $reset_code =  string_parse($_POST['reset_code']);
    $email =  string_parse($_POST['email']);

    if (!is_datas_set($_POST, array('user_id', 'new_password', 'confirm_password'))) {
        redirect_to("/View/form_reset_password.php?email=$email&reset_code=$reset_code", "error_msg", "Please complete the form");
    }

    if (!is_numeric($_POST['user_id']))
        redirect_to("/View/form_reset_password.php?email=$email&reset_code=$reset_code", "error_msg", "Invalid User Id");

    $user_id = $_POST['user_id'];
    $new_password =  string_parse($_POST['new_password']);
    $confirm_password =  string_parse($_POST['confirm_password']);

    if (!password_regex_check($new_password)) {
        redirect_to("/View/form_reset_password.php?email=$email&reset_code=$reset_code", "error_msg", "Password must contains at least 5 characters, 1 uppercase and one digit");
    }

    if ($new_password !== $confirm_password)
        redirect_to("/View/form_reset_password.php?email=$email&reset_code=$reset_code", "error_msg", "Two passwords must be same");

    $current_user = find_user(array("user_id"), "user_id", $user_id);
    if (!$current_user) 
        redirect_to("/View/form_reset_password.php?email=$email&reset_code=$reset_code", "error_msg", "User not found");
    
    change_user_field($user_id, "password", password_hash($new_password, PASSWORD_DEFAULT));
    reset_password_reset_code($user_id);
    redirect_to("/View/login.php", "notif_success", "Password has been reset");
?>