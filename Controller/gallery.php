<?php

    require_once("../Controller/redirect.php");
    require_once("../Model/user_sql.php");
    session_start();

    if (!isset($_SESSION['logged'])) {
        redirect_to("/View/login.php");
    }

    $req_error = false;
    $posts = get_all_post();
    // $comments = get_comments_from_post();
    $count_comment = get_comment_number(1);
    $count_like = get_like_number(1);
    if ($posts == false) {
        $req_error = true;
    }

    // print_r($posts);

?>