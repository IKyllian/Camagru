<?php
    require_once('./redirect.php');
    // echo('TEST');
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
    if (isset($_POST['action']) && isset($_POST['post_id'])) {
        $action = $_POST['action'];
        $post_id = $_POST['post_id'];
        $user_id = $_SESSION['id'];
        
        if (check_post_exist($post_id)) {
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
        }
    }
?>