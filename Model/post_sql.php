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
    $sql = "SELECT posts.*, users.username
            FROM posts
            INNER JOIN users
            ON posts.user_id=users.user_id
            WHERE post_id=:post_id";

    $statement = db_connection()->prepare($sql);
    $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $statement->execute();

    return $statement->fetch();
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

?>