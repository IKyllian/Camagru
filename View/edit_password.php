<?php
    require_once(__DIR__."/../Controller/log_check.php");
    require_once(__DIR__."/../Model/user_sql.php");

    $user_id = $_SESSION['id'];
    $user = find_user(array("email, username, active_notif"), "user_id", $user_id);
    
    $error = isset($_SESSION['error_msg']) ? $_SESSION['error_msg'] : null;
    unset($_SESSION['error_msg']);

    if (!$user) {
        redirect_to("/index.php");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Password</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/edit_profile.css">
    </head>

    <body>
        <?php require_once(__DIR__.'/header.php') ?>
        <div class="page-container">
            <div class="edit-wrapper">
                <div class="edit-container">
                    <p class="edit-title"> Reset Password</p>
                    <?php if ($error): ?>
                        <p> <?= $error ?> </p>
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
    </body>
</html>