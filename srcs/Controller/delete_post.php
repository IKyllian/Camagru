<?php
    require_once(__DIR__.'/log_check.php');
    require_once(__DIR__.'/parse.php');
    require_once(__DIR__.'/../Model/post_sql.php');
    require_once(__DIR__.'/redirect.php');

    if (!is_datas_set($_POST, array('post_id'))) {
        echo json_encode(array('status' => false));
    } else {
        $post_id = $_POST['post_id'];
        $user_id = $_SESSION['id'];

        $post = get_post_by_id($post_id);
        if ($post) {
            $pict_path = $post['picture_path'];
            if ($post && $user_id === $post['user_id']) {
                if (delete_post($post_id, $user_id)) {
                    $env = parse_ini_file('../.env');
                    unlink($pict_path);
                    echo json_encode(array('status' => true, 'location' => "{$env["PATH"]}/View/gallery.php"));
                }
                else
                    echo json_encode(array('status' => false));
            } else {
                echo json_encode(array('status' => false));
            }
        } else
            echo json_encode(array('status' => false));
    }
?>