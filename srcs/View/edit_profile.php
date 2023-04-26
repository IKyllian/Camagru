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
        <title>Edit Profile</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="#" />
        <link rel="stylesheet" href="../css/form.css">
    </head>

    <body>
        <?php require_once(__DIR__.'/header.html') ?>
        <div class="page-container">
            <div class="form-wrapper">
                <div class="form-container">
                    <p class="form-title"> Edit Informations</p>
                    <?php if ($error): ?>
                        <p class="msg-error"> <?= $error ?> </p>
                    <?php endif; ?>
                    <form method="post" action="../Controller/edit_profile.php">
                        <label>
                            Username:
                            <input type="text" name="username" value="<?= $user['username'] ?>" />
                        </label>
                        <label>
                            Email:
                            <input type="text" name="email" value="<?= $user['email'] ?>" />
                        </label>
                        <label>
                            Notification:
                            <div>
                                <label for="enable"> Activé </label>
                                <input
                                    name="notif"
                                    type="radio"
                                    value="enable"
                                    <?php if ($user['active_notif']): ?>
                                        checked
                                    <?php endif; ?>
                                />
                                <label for="disable"> Desactivé </label>
                                <input
                                    name="notif"
                                    type="radio"
                                    value="disable"
                                    <?php if (!$user['active_notif']): ?>
                                        checked
                                    <?php endif; ?>
                                />
                            </div>
                        </label>
                        <input type="submit" value="Confirm Informations" />
                    </form>
                </div>
            </div>
        </div>
        <?php require_once(__DIR__.'/footer.html') ?>
    </body>
</html>