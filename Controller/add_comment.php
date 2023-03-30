<?php

    require_once('./redirect.php');
    require_once('../Model/user_sql.php');
    session_start();

    function validate_data($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if (!isset($_SESSION['id']))
        redirect_to("/View/login.php");
    if (isset($_POST['comment']) && isset($_POST['post_id'])) {
        $comment = validate_data($_POST['comment']);
        $post_id = $_POST['post_id']; // Check if number;

        if (create_comment($post_id, $_SESSION['id'], $comment))
            echo "success";
        else
            echo "failed";
    }

?>