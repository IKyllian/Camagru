<?php
require_once(__DIR__.'/../connection.php');

function get_comment_number(int $post_id) {
    $sql = "SELECT COUNT(*) FROM comments WHERE post_id=:post_id";
    $statement = db_connection()->prepare($sql);
    $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchColumn();
}

function get_comments_from_post(int $post_id) {
    $sql = "SELECT comments.*, users.username
            FROM comments
            INNER JOIN users
            ON comments.user_id=users.user_id
            WHERE post_id=:post_id";

    $statement = db_connection()->prepare($sql);
    $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $statement->execute();

    return $statement->fetchAll();
}

function create_comment(int $post_id, int $user_id, string $comment) {
    $conn = db_connection();
    $sql = "INSERT INTO comments (content, post_id, user_id) VALUES (:content, :post_id, :user_id)";

    $statement = $conn->prepare($sql);
    $statement->bindValue(':content', $comment, PDO::PARAM_STR);
    $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);

    $ret = $statement->execute();

    if ($ret) {
        return get_comment_by_id($conn->lastInsertId());
    } else
        return false;
}

function get_comment_by_id($comment_id) {
    $sql = "SELECT comments.*, users.username
            FROM comments
            INNER JOIN users
            ON comments.user_id=users.user_id
            WHERE comment_id=:comment_id";

    $statement = db_connection()->prepare($sql);
    $statement->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetch();
}

?>