<?php
    require_once(__DIR__."/../Model/user_sql.php");
    require_once(__DIR__."/parse.php");
    require_once(__DIR__."/user_utils.php");
    session_start();

    function send_activation_email(string $email, string $activation_code): void
    {
        // create the activation link
        $env = parse_ini_file('../.env');

        $app_url = $env["MAIL_URL"];
        $email_address = $env["MAIL_ADDRESS"];

        $activation_link = $app_url . "/activate.php?email=$email&activation_code=$activation_code";

        // set email subject & body
        $subject = 'Please activate your account';
        $message = "
        <html>
            <head>
                <title>HTML email</title>
            </head>
            <body>
                <p> Hi, </p>
                <p> Please click the following link to activate your account: </p>
                <a href='{$activation_link}'> </a>
            </body>
        </html>";
        // $message = <<<MESSAGE
        //         Hi,
        //         Please click the following link to activate your account:
        //         $activation_link
        //         MESSAGE;
        // email header

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        // $headers =  "From: <{$email_address}>" . "\r\n" .
        //             "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        // $header = "From: " . SENDER_EMAIL_ADDRESS;

        // send the email
        mail($email, $subject, $message, $headers);

    }

    if (!is_datas_set($_POST, array('username', 'email', 'password')))
        redirect_to("/View/register.php", "error_msg", "Please complete the registration form");

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        redirect_to("/View/register.php", "error_msg", "Email is not valid");

    $email = string_parse($_POST['email']);
    $username = string_parse($_POST['username']);
    $password = string_parse($_POST['password']);

    if (find_user(array("user_id"), "username", $username) != false)
        redirect_to("/View/register.php", "error_msg", "Username already exist");

    if (find_user(array("user_id"), "email", $email) != false)
        redirect_to("/View/register.php", "error_msg", "Email already exist");

    $activation_code = generate_activation_code();
    if (user_register($username, $email, $password, $activation_code)) {
        // send_activation_email($email, $activation_code);
        header("Location: /Controller/activate.php?email={$email}&activation_code={$activation_code}");
        // header("Location: /View/login.php");
        exit;
    }

?>
