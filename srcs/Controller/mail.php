<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__.'/../PHPMailer/Exception.php';
require __DIR__.'/../PHPMailer/PHPMailer.php';
require __DIR__.'/../PHPMailer/SMTP.php';

function sendmail($mailAdress, $subject, $body, $img)
{
    $mail = new PHPMailer(true);

    try
    {
        $env = parse_ini_file('../.env');
        $mail_address = $env["MAIL_ADDRESS"];
        $mail_mdp = $env["MAIL_MDP"];

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $mail_address;
        $mail->Password   = $mail_mdp;
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        $mail->setFrom('no_reply@camagru.com', 'Camagru Administrator');
        $mail->addAddress($mailAdress);
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        if ($img)
            $mail->AddEmbeddedImage($img, 'post_img');
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->send();
        return 1;
    }
    catch (Exception $e)
    {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return 0;
    }
}

?>