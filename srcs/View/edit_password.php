<?php
    require_once(__DIR__."/../Controller/log_check.php");
    require_once(__DIR__."/../Model/user_sql.php");

    $user_id = $_SESSION['id'];
    $user = find_user(array("email, username, active_notif"), "user_id", $user_id);
    
    $error = isset($_SESSION['error_msg']) ? $_SESSION['error_msg'] : null;
    unset($_SESSION['error_msg']);

    if (!$user) {
        redirect_to("/View/home.php");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Password</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="#" />
        <link rel="stylesheet" href="../css/form.css">
    </head>

    <body>
        <?php require_once(__DIR__.'/header.php') ?>
        <div class="page-container">
            <div class="form-wrapper">
                <div class="form-container">
                    <p class="form-title"> Reset Password</p>
                    <?php if ($error): ?>
                        <p class="msg-error"> <?= $error ?> </p>
                    <?php endif; ?>
                    <form method="post" action="../Controller/edit_password.php">
                        <label>
                            Current password:
                            <input type="password" name="current_password" />
                        </label>
                        <label>
                            New password:
                            <input type="password" name="new_password" />
                        </label>
                        <label>
                            Confirm password:
                            <input type="password" name="confirm_password" />
                        </label>
                        <input type="submit" value="Reset" />
                    </form>
                </div>
            </div>
        </div>
        <?php require_once(__DIR__.'/footer.html') ?>
    </body>
</html>