<?php
    require_once(__DIR__.'/log_check.php');
    require_once(__DIR__.'/redirect.php');
    require_once(__DIR__.'/../Model/user_sql.php');
    require_once(__DIR__.'/../Model/comment_sql.php');
    require_once(__DIR__.'/../Model/post_sql.php');
    require_once(__DIR__.'/parse.php');

    if (is_datas_set(array($_POST['comment'], $_POST['post_id']))) {
        $comment = string_parse($_POST['comment']);
        $post_id = $_POST['post_id']; // Check if number;

        if (check_post_exist($post_id) && find_user(array('user_id'), 'user_id', $_SESSION['id']) != false) {
            if (create_comment($post_id, $_SESSION['id'], $comment))
                echo "success";
            else
                echo "failed";
        }       
    }
?>