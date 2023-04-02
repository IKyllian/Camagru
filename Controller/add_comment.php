<?php
    require_once(__DIR__.'/log_check.php');
    require_once(__DIR__.'/redirect.php');
    require_once(__DIR__.'/../Model/user_sql.php');
    require_once(__DIR__.'/../Model/comment_sql.php');
    require_once(__DIR__.'/../Model/post_sql.php');

    function validate_data($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if (isset($_POST['comment']) && isset($_POST['post_id'])) {
        $comment = validate_data($_POST['comment']);
        $post_id = $_POST['post_id']; // Check if number;

        if (check_post_exist($post_id) && find_user(array('user_id'), 'user_id', $_SESSION['id']) != false) {
            if (create_comment($post_id, $_SESSION['id'], $comment))
                echo "success";
            else
                echo "failed";
        }       
    }
?>