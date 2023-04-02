<?php
    require_once(__DIR__.'/log_check.php');
    require_once(__DIR__."/../Controller/redirect.php");
    require_once(__DIR__.'/../Model/like_sql.php');
    require_once(__DIR__.'/../Model/comment_sql.php');
    require_once(__DIR__.'/../Model/post_sql.php');

    class Post {
        public $post_data;
        public $nb_comments;
        public $nb_likes;
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