<?php
    require_once(__DIR__."/../Controller/redirect.php");
    require_once(__DIR__."/../Controller/parse.php");
    require_once(__DIR__."/../Model/user_sql.php");
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!is_datas_set($_GET, array('reset_code', 'email'))) {
        redirect_to("/View/login.php");
    }

    $reset_code = string_parse($_GET['reset_code']);
    $email = string_parse($_GET['email']);

    $user = find_reset_code_user($reset_code, $email);

    if (!$user)
        redirect_to("/View/login.php");

    if (isset($_SESSION['logged'])) {
        redirect_to("/View/home.php");
    }

    $form_error = !empty($_SESSION['error_msg']) ? $_SESSION['error_msg'] : NULL;
    unset($_SESSION['error_msg']); 
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Reset Password </title>
        <link rel="shortcut icon" href="#" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/form.css">
    </head>

    <body>
        <div class="page-container">
            <div class="form-wrapper">
                <div class="form-container">
                    <p class="form-title"> Reset Password </p>
                    <?php if ($form_error): ?>
                        <p class="msg-error"> <?php echo $form_error ?> </p>
                    <?php endif; ?>
                    <form method="post" action="../Controller/reset_password.php">
                        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>" />
                        <input type="hidden" name="email" value="<?= $email ?>" />
                        <input type="hidden" name="reset_code" value="<?= $reset_code?>" />
                        <label>
                            New Password
                            <input type="password" name="new_password" />
                        </label>
                        <label>
                            Confirm Password
                            <input type="password" name="confirm_password" />
                        </label>
                        <input type="submit" value="Reset" />
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>