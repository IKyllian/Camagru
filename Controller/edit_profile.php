<?php
    require_once(__DIR__."/log_check.php");
    require_once(__DIR__."/../Model/user_sql.php");
    require_once(__DIR__."/parse.php");

    if (!is_datas_set(array($_POST['username'], $_POST['email'], $_POST['notif']))) {
        redirect_to("/View/edit_profile.php", "error_msg", "Please complete the form");
    }
    
    $username =  string_parse($_POST['username']);
    $email =  strtolower(string_parse($_POST['email']));
    $user_id = $_SESSION['id'];
    $current_user = find_user(array("user_id, username, email"), "user_id", $user_id);

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
        change_user_field($user_id, "username", $username);
    }

    if ($email !== $current_user['email']) {
        if (find_user(array("user_id"), "email", $email))
            redirect_to("/View/edit_profile.php", "error_msg", "Email already exist");
        change_user_field($user_id, "email", $email);
    }

    change_user_field($user_id, "active_notif", $notif);
    redirect_to("/View/profile.php");
    
?>