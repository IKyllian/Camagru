<?php
    require_once(__DIR__."/../Controller/redirect.php");
    require_once(__DIR__."/../Controller/parse.php");
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['logged'])) {
        redirect_to("/index.php");
    }
    
    $form_error = !empty($_SESSION['error_msg']) ? $_SESSION['error_msg'] : NULL;
    unset($_SESSION['error_msg']);
  
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/form.css">
    </head>
    <body>
        <div class="page-container">
            <div class="form-wrapper">
                <div class="form-container">
                    <p class="form-title"> Login </p>
                    <?php if ($form_error != NULL): ?>
                        <p> <?php echo $form_error ?> </p>
                    <?php endif; ?>
                    <form id='login-form' method="post" action="../Controller/signin.php">
                        <label>
                            Username
                            <input type="text" name="username" />
                        </label>
                        <label>
                            Password
                            <input type="password" name="password" />
                        </label>            
                        <input type="submit" value="Signin" />
                    </form>
                    <a class="switch-btn" href="register.php"> Pas de compte ? Créez en un </a> 
                </div>
            </div>
        </div>
    </body>
</html>