<?php
    require_once(__DIR__."/log_check.php");
    require_once(__DIR__."/../Model/user_sql.php");

    if (!isset($_POST['email']) && !empty($_POST['email'])) {
        echo 'parse error';
        return ;
    }

    $input_value = $_POST['email'];
    $user_id = $_SESSION['id'];

    if (change_user_field($user_id, "username", $input_value))
        echo 'success';
    else
        echo 'error';

?>