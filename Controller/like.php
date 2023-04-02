<?php
    require_once(__DIR__.'/log_check.php');
    require_once(__DIR__.'/redirect.php');
    require_once(__DIR__.'/../Model/user_sql.php');
    require_once(__DIR__.'/../Model/post_sql.php');
    require_once(__DIR__.'/../Model/like_sql.php');

    function validate_data($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if (isset($_POST['action']) && isset($_POST['post_id'])) {
        $action = $_POST['action'];
        $post_id = $_POST['post_id'];
        $user_id = $_SESSION['id'];
        
        if (check_post_exist($post_id) && find_user(array('user_id'), 'user_id', $_SESSION['id']) != false) {
            if ($action === "like") {
                if (add_like($post_id, $user_id)) {
                    echo('success');
                    return ;
                }
            } else if ($action == "unlike") {
                if (remove_like($post_id, $user_id)) {
                    echo('success');
                    return ;
                }  
            }
        } else
            echo('error');
    }
?>