<?php
require_once(__DIR__.'/../connection.php');

function get_comment_number(int $post_id) {
    $sql = "SELECT COUNT(*) FROM comments WHERE post_id={$post_id}";
    $statement = db_connection()->query($sql);
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
    $sql = "INSERT INTO comments (content, post_id, user_id) VALUES (:content, :post_id, :user_id)";

    $statement = db_connection()->prepare($sql);

    $data = [
        ':content' => $comment,
        ':post_id' => $post_id,
        ':user_id' => $user_id,
    ];

    return $statement->execute($data);
}

?>