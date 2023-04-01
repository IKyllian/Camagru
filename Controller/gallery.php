<?php
    require_once("../Controller/redirect.php");
    require_once('../Model/like_sql.php');
    require_once('../Model/comment_sql.php');
    require_once('../Model/post_sql.php');
    session_start();

    class Post {
        public $post_data;
        public $nb_comments;
        public $nb_likes;
    }

    if (!isset($_SESSION['logged'])) {
        redirect_to("/View/login.php");
    }

    $req_error = false;
    $all_posts = get_all_post();
    $posts = array();
    
    foreach($all_posts as $item) {
        $new_post = new Post();
        $new_post->post_data = $item;
        $new_post->nb_comments = get_comment_number($item['post_id']);
        $new_post->nb_likes = get_like_number($item['post_id']);
        array_push($posts, $new_post);
    }
?>