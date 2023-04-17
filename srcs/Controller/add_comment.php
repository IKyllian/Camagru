<?php
    require_once(__DIR__.'/log_check.php');
    require_once(__DIR__.'/redirect.php');
    require_once(__DIR__.'/../Model/user_sql.php');
    require_once(__DIR__.'/../Model/comment_sql.php');
    require_once(__DIR__.'/../Model/post_sql.php');
    require_once(__DIR__.'/parse.php');

    if (is_datas_set($_POST, array('comment', 'post_id'))) {
        $comment = string_parse($_POST['comment']);
        $post_id = $_POST['post_id']; // Check if number;

        if (check_post_exist($post_id) && find_user(array('user_id'), 'user_id', $_SESSION['id']) != false) {
            $new_comment = create_comment($post_id, $_SESSION['id'], $comment);
            if ($new_comment) {
                echo json_encode(array('status' => true, 'comment' => $new_comment));
            }
            else
                echo json_encode(array('status' => false));
        }
    }
?>