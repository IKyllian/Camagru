<?php
    require_once("../Controller/redirect.php");
    require_once("../Model/user_sql.php");
    session_start();

    if (!isset($_SESSION['logged'])) {
        redirect_to("/View/login.php");
    }

    if (!isset($_GET['post_id']) || empty($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
        redirect_to('/View/gallery.php');
    }

    $post_id = $_GET['post_id'];

    $post = get_post_by_id($post_id);
    $comments = get_comments_from_post($post_id);
    $nb_like = get_like_number($post_id);

?>