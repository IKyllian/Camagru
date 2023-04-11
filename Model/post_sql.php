<?php
require_once(__DIR__.'/../connection.php');
function check_post_exist(int $post_id) {
    $sql = "SELECT post_id
            FROM posts
            WHERE post_id=:post_id";

    $statement = db_connection()->prepare($sql);
    $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $statement->execute();

    if ($statement->rowCount() > 0)
        return true;
    else
        return false;
}

function get_post_by_id(int $post_id) {
    $sql = "SELECT posts.*, users.username, users.user_id
            FROM posts
            INNER JOIN users
            ON posts.user_id=users.user_id
            WHERE post_id=:post_id";

    $statement = db_connection()->prepare($sql);
    $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $statement->execute();

    return $statement->fetch();
}

function get_posts_per_page($current_page, $post_per_page) {
    $sql = "SELECT posts.*, users.username
            FROM posts
            LIMIT $post_per_page, ($current_page - 1)*$post_per_page
            INNER JOIN users
            ON posts.user_id=users.user_id";

    $statement = db_connection()->prepare($sql);
    $statement->execute();

    return $statement->fetchAll();
}

function get_all_post() {
    $sql = "SELECT posts.*, users.username
            FROM posts
            INNER JOIN users
            ON posts.user_id=users.user_id";

    $statement = db_connection()->prepare($sql);
    $statement->execute();

    return $statement->fetchAll();
}

function get_user_post_nbr($user_id) {
    $sql = "SELECT COUNT(*) FROM posts WHERE user_id=:user_id";
    $statement = db_connection()->prepare($sql);
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchColumn();
}

function get_posts_nbr() {
    $sql = "SELECT COUNT(*) FROM posts";
    $statement = db_connection()->prepare($sql);
    $statement->execute();
    return $statement->fetchColumn();
}

function delete_post(int $post_id, int $user_id) {
    $sql = "DELETE FROM posts WHERE post_id=:post_id and user_id=:user_id";
    $statement = db_connection()->prepare($sql);
    $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    return $statement->execute();
}

?>