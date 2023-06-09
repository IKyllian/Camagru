<?php
    require_once(__DIR__."/log_check.php");
    require_once(__DIR__."/../Model/user_sql.php");
    require_once(__DIR__."/parse.php");

    if (!is_datas_set($_POST, array('current_password', 'new_password', 'confirm_password'))) {
        redirect_to("/View/edit_profile.php", "error_msg", "Please complete the form");
    }
    
    $current_password =  string_parse($_POST['current_password']);
    $new_password =  string_parse($_POST['new_password']);
    $confirm_password =  string_parse($_POST['confirm_password']);

    if (!password_regex_check($new_password)) {
        redirect_to("/View/edit_password.php", "error_msg", "Password must contains at least 5 characters, 1 uppercase and one digit");
    }

    if ($new_password !== $confirm_password)
        redirect_to("/View/edit_password.php", "error_msg", "Two passwords must be same");

    $current_user = find_user(array("user_id, password"), "user_id", $logged_user_id);
    if (!$current_user) 
        require(__DIR__."/logout.php");
    
    if (!password_verify($current_password, $current_user['password']))
        redirect_to("/View/edit_password.php", "error_msg", "Wrong password");

    if (password_verify($new_password, $current_user['password']))
        redirect_to("/View/edit_password.php", "error_msg", "New password must be different from the current one");
    
    change_user_field($logged_user_id, "password", password_hash($new_password, PASSWORD_DEFAULT));
    redirect_to("/View/profile.php?id={$logged_user_id}");
    
?>