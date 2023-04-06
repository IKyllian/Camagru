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
        <!-- <script type='module'>
            let form = document.getElementById('login-form');

            if (form) {
                form.addEventListener('submit', e => {
                    e.preventDefault();

                    const formFields = e.target.elements;
                    const username = formFields.namedItem("username").value;
                    const password = formFields.namedItem("password").value;
                    const username_parse = username.replace(/</g, "&lt;").replace(/>/g, "&gt;");
                    const password_parse = password.replace(/</g, "&lt;").replace(/>/g, "&gt;");

                    let commentData = new FormData();
                    commentData.append('username', username_parse);
                    commentData.append('password', password_parse);
                    let XHR = new XMLHttpRequest();
                    XHR.onreadystatechange = function () {
                        if (this.readyState === 4 && this.status === 200) {
                            console.log(this.responseText); }
                        };
                    XHR.open('POST', '../Controller/signin.php', true);
                    XHR.send(commentData);
                })
            }
        </script> -->

    </head>

    <body>
        <h1> Login </h1>
        <?php if ($form_error != NULL): ?>
            <p> <?php echo $form_error ?> </p>
        <?php endif; ?>
        <form id='login-form' method="post" action="../Controller/signin.php">
            <input type="text" name="username" />
            <input type="password" name="password" />
            <input type="submit" />
        </form>
        <a href="register.php"> Signup </a> 
    </body>
</html>