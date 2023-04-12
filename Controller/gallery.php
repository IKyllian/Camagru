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

    $page = 1;
    $post_per_page = 4;
    $post_nbr = get_posts_nbr();
    $page_nbr = ceil($post_nbr/$post_per_page);

    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $get_value = intval($_GET['page']);
        if ($get_value > 0 && $get_value <= $page_nbr) {
            $page = $get_value;
        }
    }

    $req_error = false;
    $all_posts = get_posts_per_page(($page - 1)*$post_per_page, $post_per_page);
    $posts = array();
    
    foreach($all_posts as $item) {
        $new_post = new Post();
        $new_post->post_data = $item;
        $new_post->nb_comments = get_comment_number($item['post_id']);
        $new_post->nb_likes = get_like_number($item['post_id']);
        array_push($posts, $new_post);
    }
?>