<?php
    require_once(__DIR__.'/log_check.php');
    require_once(__DIR__.'/redirect.php');
    require_once(__DIR__.'/../Model/user_sql.php');
    require_once(__DIR__.'/../Model/post_sql.php');
    require_once(__DIR__.'/../Model/like_sql.php');
    require_once(__DIR__.'/parse.php');

    if (is_datas_set($_POST, array('action', 'post_id'))) {
        $action = $_POST['action'];
        if (is_numeric($_POST['post_id'])) {
            $post_id = $_POST['post_id'];
            if (check_post_exist($post_id) && find_user(array('user_id'), 'user_id', $_SESSION['id']) != false) {
                if ($action === "like") {
                    if (add_like($post_id, $logged_user_id)) {
                        echo json_encode(array('status' => true));
                        return ;
                    }
                } else if ($action == "unlike") {
                    if (remove_like($post_id, $logged_user_id)) {
                        echo json_encode(array('status' => true));
                        return ;
                    }  
                }
            } else
                echo json_encode(array('status' => false));
        } else 
            echo json_encode(array('status' => false));
    } else
        echo json_encode(array('status' => false));
?>