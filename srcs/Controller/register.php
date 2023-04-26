<?php
    require_once(__DIR__."/../Model/user_sql.php");
    require_once(__DIR__."/parse.php");
    require_once(__DIR__."/user_utils.php");
    require_once(__DIR__."/mail.php");
    session_start();

    function send_activation_email(string $email, string $activation_code, $username)
    {
        $env = parse_ini_file('../.env');
        $app_url = $env["APP_URL"];
        $activation_link = $app_url . "/Controller/activate.php?email=$email&activation_code=$activation_code";

        $subject = 'Please activate your account';
        $message = "
        <html>
            <head>
                <title>HTML email</title>
            </head>
            <body>
                <p> Hi, $username </p>
                <p> Please click <a href='{$activation_link}'> here </a> to activate your account </p>
            </body>
        </html>";

        if (sendmail($email, $subject, $message, false))
            return 1;
        return 0;
    }

    if (!is_datas_set($_POST, array('username', 'email', 'password')))
        redirect_to("/View/register.php", "error_msg", "Please complete the registration form");

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        redirect_to("/View/register.php", "error_msg", "Email is not valid");

    $email = string_parse($_POST['email']);
    $username = string_parse($_POST['username']);
    $password = string_parse($_POST['password']);

    if (!username_check($username)) {
        redirect_to("/View/register.php", "error_msg", "Username must contains at least 2 characters");
    }

    if (!password_regex_check($password)) {
        redirect_to("/View/register.php", "error_msg", "Password must contains at least 5 characters, 1 uppercase and one digit");
    }

    if (find_user(array("user_id"), "username", $username) != false)
        redirect_to("/View/register.php", "error_msg", "Username already exist");

    if (find_user(array("user_id"), "email", $email) != false)
        redirect_to("/View/register.php", "error_msg", "Email already exist");

    $activation_code = generate_activation_code();
    if (user_register($username, $email, $password, $activation_code)) {
        if (send_activation_email($email, $activation_code, $username))
            redirect_to("/View/login.php", "notif_success", "A mail has been send to activate your account");
        else
            redirect_to("/View/register.php", "error_msg", "Failed to send mail");
    }
?>
