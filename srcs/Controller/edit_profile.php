<?php
    require_once(__DIR__."/log_check.php");
    require_once(__DIR__."/../Model/user_sql.php");
    require_once(__DIR__."/parse.php");

    if (!is_datas_set($_POST, array('username', 'email', 'notif'))) {
        redirect_to("/View/edit_profile.php", "error_msg", "Please complete the form");
    }
    
    $username =  string_parse($_POST['username']);
    $email =  strtolower(string_parse($_POST['email']));
    $current_user = find_user(array("user_id, username, email"), "user_id", $logged_user_id);

    if (!username_check($username)) {
        redirect_to("/View/edit_profile.php", "error_msg", "Username must contains at least 2 characters");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        redirect_to("/View/edit_profile.php", "error_msg", "Email is not valid");

    if (!$current_user) 
        require(__DIR__."/logout.php");

    $notif = null;
    if ($_POST['notif'] === 'enable')
        $notif = 1;
    else if ($_POST['notif'] === 'disable')
        $notif = 0;
    
    if ($notif === null)
        redirect_to("/View/edit_profile.php", "error_msg", "Error with notif value");

    if (strtolower($username) !== strtolower($current_user['username'])) {
        if (find_user(array("user_id"), "username", $username))
            redirect_to("/View/edit_profile.php", "error_msg", "Username already exist");
        change_user_field($logged_user_id, "username", $username);
    }

    if ($email !== $current_user['email']) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            redirect_to("/View/edit_profile.php", "error_msg", "Invalid email");
        if (find_user(array("user_id"), "email", $email))
            redirect_to("/View/edit_profile.php", "error_msg", "Email already exist");
        change_user_field($logged_user_id, "email", $email);
    }

    change_user_field($logged_user_id, "active_notif", $notif);
    redirect_to("/View/profile.php?id={$logged_user_id}");
    
?>