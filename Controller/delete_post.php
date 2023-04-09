<?php
    require_once(__DIR__.'/log_check.php');
    require_once(__DIR__.'/parse.php');
    require_once(__DIR__.'/../Model/post_sql.php');

    if (!is_datas_set($_POST, array('post_id'))) {
        echo 'error';
    } else {
        $post_id = $_POST['post_id'];
        $user_id = $_SESSION['id'];

        $post = get_post_by_id($post_id);
        $pict_path = $post['picture_path'];
        if ($post && $user_id === $post['user_id']) {
            if (delete_post($post_id, $user_id)) {
                unlink($pict_path);
                echo "success";
            }
            else
                echo "error";
        } else {
            echo 'error';
        }
    }


?>