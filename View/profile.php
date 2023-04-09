<?php
    require_once(__DIR__."/../Controller/profile.php");

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Profile</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="module" src="../js/profile.js"> </script>
    </head>

    <body>
        <?php require_once(__DIR__.'/header.php') ?>
        <p> username: <?= $user['username'] ?></p>
        <p> email: <?= $user['email'] ?></p>
        <p> created at: <?= $date->format('d-m-Y') ?></p>
        <p> Notif: <?= $user['active_notif'] ? "Activé" : "Désactivé" ?> </p>
        <a href="/View/edit_password.php"> Reset Password </a>
        <a href="/View/edit_profile.php"> Edit profile </a>
        
    </body>
</html>