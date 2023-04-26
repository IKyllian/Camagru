<?php
    require_once(__DIR__."/../Controller/redirect.php");
    require_once(__DIR__."/../Controller/parse.php");
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['logged'])) {
        redirect_to("/View/home.php");
    }
    
    $form_error = !empty($_SESSION['error_msg']) ? $_SESSION['error_msg'] : NULL;
    unset($_SESSION['error_msg']);

    $notif_success = !empty($_SESSION['notif_success']) ? $_SESSION['notif_success'] : NULL;
    unset($_SESSION['notif_success']);    
  
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="#" />
        <link rel="stylesheet" href="../css/form.css">
        <link rel="stylesheet" href="../css/notif.css">
        <script src="https://kit.fontawesome.com/f9678243a8.js" crossorigin="anonymous"></script>
        <script type="module" src="../js/login.js"> </script>
    </head>
    <body>
        <div class="form-sign-wrapper">
            <div class="form-container">
                <?php if ($notif_success != NULL): ?>
                    <div class="notif-container notif-success" id="notif-wrapper">
                        <div class="notif-icon notif-icon-check">
                            <i class="fas fa-check"></i>
                        </div>
                        <p> <?= $notif_success ?> </p>
                        <i class="fas fa-xmark" id="notif-icon-delete"></i>
                    </div>
                <?php endif; ?>
                <p class="form-title"> Login </p>
                <?php if ($form_error != NULL): ?>
                    <p class="msg-error"> <?php echo $form_error ?> </p>
                <?php endif; ?>
                <form id='login-form' method="post" action="../Controller/signin.php">
                    <label>
                        Username
                        <input type="text" name="username" />
                    </label>
                    <label>
                        Password
                        <input type="password" name="password" />
                        <a class="forgot-pass" href="form_send_mail_password.php"> Forgot password ?</a>
                    </label>            
                    <input type="submit" value="Signin" />
                </form>
                <a class="switch-btn" href="register.php"> Pas de compte ? Créez en un </a> 
            </div>
        </div>
    </body>
</html>