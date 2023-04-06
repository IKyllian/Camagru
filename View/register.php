<?php
    require_once(__DIR__."/../Controller/redirect.php");
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
        <title>Register</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <h1> Register </h1>
        <?php if ($form_error): ?>
            <p> <?php echo $form_error ?> </p>
        <?php endif; ?>
        <form method="post" action="../Controller/register.php">
            <label>
                Username
                <input type="text" name="username" />
            </label>
            <label>
                Email
                <input type="text" name="email" />
            </label>
            <label>
                Password
                <input type="password" name="password" />
            </label>
            <input type="submit" />
        </form>
        <a href="login.php"> Signin </a>
    </body>
</html>