<?php
    require_once(__DIR__."/log_check.php");
    require_once(__DIR__."/../Model/user_sql.php");

    $user_id = $_SESSION['id'];
    $user = find_user(array("email, username, active_notif, created_at"), "user_id", $user_id);

    if (!$user) {
        redirect_to("/index.php");
    }

    $date = new DateTimeImmutable($user['created_at']);

?>