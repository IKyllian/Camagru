<?php
    require_once(__DIR__."/parse.php");
    require_once(__DIR__."/../Model/user_sql.php");
    session_start();

    if (!is_datas_set($_POST, array('email'))) {
        redirect_to("/View/form_send_mail_password.php", "error_msg", "Please complete the form");
    }

    $email = string_parse($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        redirect_to("/View/form_send_mail_password.php", "error_msg", "Email is not valid");

    $user = find_user(array("user_id"), "email", $email);

    if (!$user != false)
        redirect_to("/View/form_send_mail_password.php", "error_msg", "Email not found");

    $env = parse_ini_file('../.env');

    $app_url = $env["MAIL_URL"];
    $reset_code = generate_activation_code();
    $hash_code = password_hash($reset_code, PASSWORD_DEFAULT);

    change_user_field($user['user_id'], "password_reset_code", $hash_code);

    // add_password_reset_code($user['user_id'], $reset_code);
    $reset_link = $app_url . "/reset_password.php?email=$email&reset_code=$reset_code";

    header("Location: /View/form_reset_password.php?email=$email&reset_code=$reset_code");
?>