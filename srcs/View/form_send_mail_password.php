<?php
    require_once(__DIR__."/../Controller/redirect.php");
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['logged'])) {
        redirect_to("/View/home.php");
    }

    $form_error = !empty($_SESSION['error_msg']) ? $_SESSION['error_msg'] : NULL;
    unset($_SESSION['error_msg']); 
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Send Mail</title>
        <link rel="shortcut icon" href="#" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/form.css">
    </head>

    <body>
        <div class="page-container">
            <div class="form-wrapper">
                <div class="form-container">
                    <p class="form-title"> Send Mail </p>
                    <?php if ($form_error): ?>
                        <p class="msg-error"> <?php echo $form_error ?> </p>
                    <?php endif; ?>
                    <form method="post" action="../Controller/send_mail_password.php">
                        <label>
                            Mail
                            <input type="text" name="email" />
                        </label>
                        <input type="submit" value="Send" />
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>