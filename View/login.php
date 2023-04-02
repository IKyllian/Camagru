<?php
    require_once(__DIR__."/../Controller/redirect.php");
    session_start();

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
    </head>

    <body>
        <h1> Login </h1>
        <?php if ($form_error != NULL): ?>
            <p> <?php echo $form_error ?> </p>
        <?php endif; ?>
        <form method="post" action="../Controller/signin.php">
            <input type="text" name="username" />
            <input type="password" name="password" />
            <input type="submit" />
        </form>
        <a href="register.php"> Signup </a> 
    </body>
</html>