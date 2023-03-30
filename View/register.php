<?php
    require_once("../Controller/redirect.php");
    session_start();

    $form_error = !empty($_SESSION['error_msg']) ? $_SESSION['error_msg'] : NULL;
    unset($_SESSION['error_msg']); 

    if (isset($_SESSION['logged'])) {
        redirect_to("/index.php");
    }
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