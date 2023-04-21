<?php
    require_once(__DIR__."/log_check.php");
    require_once(__DIR__."/../Model/user_sql.php");
    require_once(__DIR__."/../Model/post_sql.php");
    require_once(__DIR__."/../Model/comment_sql.php");
    require_once(__DIR__."/../Model/like_sql.php");

    class Post {
        public $post_data;
        public $nb_comments;
        public $nb_likes;
    }

    if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
        redirect_to("/View/home.php");
    }

    $user_id = $_GET['id'];
    $is_user_connected = 0;
    if ($logged_user_id == $user_id)
        $is_user_connected = 1;
    $user = find_user(array("email, username, active_notif, created_at"), "user_id", $user_id);
    if (!$user) {
        redirect_to("/View/home.php");
    }

    $page = 1;
    $post_per_page = 4;
    $post_nbr = get_user_post_nbr($user_id);
    $page_nbr = ceil($post_nbr/$post_per_page);

    if (isset($_GET['page']) && !empty($_GET['page']) && is_numeric($_GET['page'])) {
        $get_value = intval($_GET['page']);
        if ($get_value > 0 && $get_value <= $page_nbr) {
            $page = $get_value;
        }
    }

    $all_user_posts = get_post_from_user($user_id, ($page - 1)*$post_per_page, $post_per_page);
    $user_posts = array();
    
    foreach($all_user_posts as $item) {
        $new_post = new Post();
        $new_post->post_data = $item;
        $new_post->nb_comments = get_comment_number($item['post_id']);
        $new_post->nb_likes = get_like_number($item['post_id']);
        array_push($user_posts, $new_post);
    }

    $date = new DateTimeImmutable($user['created_at']);

?>