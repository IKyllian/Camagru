<?php
    require_once(__DIR__."/../Controller/profile.php");

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Profile</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/profile.css">
        <script type="module" src="../js/profile.js"> </script>
    </head>

    <body>
        <?php require_once(__DIR__.'/header.php') ?>
        <div class="page-container">
            <div class="profile-wrapper">
                <div class="profile-container">
                    <p class="infos-title"> Informations personelles</p>
                    <div class="infos-wrapper">
                        <div class="info-item">
                            <span class="field"> Username: </span>
                            <span> <?= $user['username'] ?> </span>
                        </div>
                        <div class="info-item">
                            <span class="field"> Email: </span>
                            <span> <?= $user['email'] ?> </span>
                        </div>
                        <div class="info-item">
                            <span class="field"> Notification: </span>
                            <span> <?= $user['active_notif'] ? "Activée" : "Désactivée" ?> </span>
                        </div>
                        <div class="info-item">
                            <span class="field"> Created at: </span>
                            <span> <?= $date->format('d-m-Y') ?> </span>
                        </div>
                    </div>
                    <div class="buttons-wrapper">
                        <a href="/View/edit_password.php"> Reset Password </a>
                        <a href="/View/edit_profile.php"> Edit profile </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>