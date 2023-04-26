<?php
    require_once(__DIR__.'/log_check.php');
    require_once(__DIR__.'/parse.php');
    require_once(__DIR__.'/../Model/post_sql.php');
    require_once(__DIR__.'/redirect.php');

    if (!is_datas_set($_POST, array('post_id'))) {
        echo json_encode(array('status' => false));
    } else {
        if (is_numeric($_POST['post_id'])) {
            $post_id = $_POST['post_id'];    
            $post = get_post_by_id($post_id);
            if ($post) {
                $pict_path = $post['picture_path'];
                if ($post && $logged_user_id === $post['user_id']) {
                    if (delete_post($post_id, $logged_user_id)) {
                        $env = parse_ini_file('../.env');
                        unlink($pict_path);
                        echo json_encode(array('status' => true, 'location' => "{$env["APP_URL"]}/View/gallery.php"));
                    }
                    else
                        echo json_encode(array('status' => false));
                } else {
                    echo json_encode(array('status' => false));
                }
            } else
                echo json_encode(array('status' => false));
        } else
            echo json_encode(array('status' => false));
    }
?>