<?php
    require_once(__DIR__.'/log_check.php');
    require_once(__DIR__."/../Controller/redirect.php");
    require_once(__DIR__.'/../Model/like_sql.php');
    require_once(__DIR__.'/../Model/comment_sql.php');
    require_once(__DIR__.'/../Model/post_sql.php');
    require_once(__DIR__.'/parse.php');

    if (!is_datas_set($_GET, array('post_id')) || !is_numeric($_GET['post_id'])) { 
        redirect_to('/View/gallery.php');
    }

    $post_id = $_GET['post_id'];

    $post = get_post_by_id($post_id);
    if ($post) {
        $comments = get_comments_from_post($post_id);
        $nb_like = get_like_number($post_id);
        $is_post_liked = user_liked_post($post_id, $logged_user_id);
    }
?>