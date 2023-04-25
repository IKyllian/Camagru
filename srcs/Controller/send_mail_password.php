<?php
    require_once(__DIR__."/parse.php");
    require_once(__DIR__."/redirect.php");
    require_once(__DIR__."/mail.php");
    require_once(__DIR__."/../Model/user_sql.php");
    session_start();

    function send_reset_password(string $email, string $reset_link, $username)
    {
        $subject = 'Reset password';
        $message = "
        <html>
            <head>
                <title>HTML email</title>
            </head>
            <body>
                <p> Hi, $username </p>
                <p> Please click <a href='{$reset_link}'> here </a> to reset your password </p>
            </body>
        </html>";

        if (sendmail($email, $subject, $message, false))
            return 1;
        return 0;
    }

    if (!is_datas_set($_POST, array('email'))) {
        redirect_to("/View/form_send_mail_password.php", "error_msg", "Please complete the form");
    }

    $email = string_parse($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        redirect_to("/View/form_send_mail_password.php", "error_msg", "Email is not valid");

    $user = find_user(array("user_id, username"), "email", $email);

    if (!$user != false)
        redirect_to("/View/form_send_mail_password.php", "error_msg", "Email not found");

    $env = parse_ini_file('../.env');

    $app_url = $env["APP_URL"];
    $reset_code = generate_activation_code();
    $hash_code = password_hash($reset_code, PASSWORD_DEFAULT);

    change_user_field($user['user_id'], "password_reset_code", $hash_code);

    $reset_link = $app_url . "/View/form_reset_password.php?email=$email&reset_code=$reset_code";
    if (send_reset_password($email, $reset_link, $user['username']))
        redirect_to("/View/login.php", "notif_success", "Email has been send");
    else
        redirect_to("/View/form_send_mail_password.php", "error_msg", "Failed to send mail");
?>