<?php
require_once("../Model/user_sql.php");
session_start();
function is_valid_datas($username, $email, $password) {
    if (!isset($username, $password, $email)) {
        redirect_to("/View/register.php", "error_msg", "Please complete the registration form");
    }

    if (empty($username) || empty($password) || empty($email)) {
        redirect_to("/View/register.php", "error_msg", "Please complete the registration form");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_to("/View/register.php", "error_msg", "Email is not valid");
    }

    //Check password and username
    return (1);
}

function validate_data($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generate_activation_code(): string
{
    return bin2hex(random_bytes(16));
}

function send_activation_email(string $email, string $activation_code): void
{
    // create the activation link
    $env = parse_ini_file('../.env');

    $app_url = $env["MAIL_URL"];
    $email_address = $env["MAIL_ADDRESS"];

    $activation_link = $app_url . "/activate.php?email=$email&activation_code=$activation_code";

    // set email subject & body
    $subject = 'Please activate your account';
    $message = <<<MESSAGE
            Hi,
            Please click the following link to activate your account:
            $activation_link
            MESSAGE;
    // email header
    $headers =  "From: {$email_address}" . "\r\n" .
                "Reply-To: {$email_address}" . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
    // $header = "From: " . SENDER_EMAIL_ADDRESS;
    echo("header = {$headers}");


    // send the email
    $ret = mail($email, $subject, nl2br($message));
    echo("ret = {$ret}");

}

if (is_valid_datas($_POST['username'], $_POST['email'], $_POST['password']))
{
	$email = validate_data($_POST['email']);
    $username = validate_data($_POST['username']);
    $password = validate_data($_POST['password']);

    if (find_user(array("user_id"), "username", $username) != false)
        redirect_to("/View/register.php", "error_msg", "Username already exist");

    if (find_user(array("user_id"), "email", $email) != false)
        redirect_to("/View/register.php", "error_msg", "Email already exist");

    $activation_code = generate_activation_code();
    if (user_register($username, $email, $password, $activation_code)) {
        // send_activation_email($email, $activation_code);
        header("Location: /Controller/activate.php?email={$email}&activation_code={$activation_code}");
        // header("Location: /View/signin.php");
        exit;
    }
}
?>