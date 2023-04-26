<?php
    require_once(__DIR__."/../Model/user_sql.php");
    require_once(__DIR__."/parse.php");

    if (is_datas_set($_GET, array('activation_code', 'email'))) {
        $activation_code = string_parse($_GET['activation_code']);
        $email = string_parse($_GET['email']);

        $user = find_unverified_user($activation_code, $email);

        // if user exists and activate the user successfully
        if ($user && activate_user($user['user_id'])) {
            if (session_status() === PHP_SESSION_NONE)
                session_start();
            session_regenerate_id();
            $_SESSION['notif_success'] = "Account activated";
            header("Location: /View/login.php");
            exit;
        }
    }
    header("Location: /View/register.php");
    exit;

?>