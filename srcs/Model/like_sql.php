<?php
require_once(__DIR__.'/user_sql.php');
require_once(__DIR__.'/../connection.php');

function get_like_number(int $post_id) {
    $sql = "SELECT COUNT(*) FROM likes WHERE post_id=:post_id";
    $statement = db_connection()->prepare($sql);
    $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchColumn();
}

function add_like(int $post_id, int $user_id) {
    if (!user_liked_post($post_id, $user_id)) {
        $sql = "INSERT INTO likes (post_id, user_id) VALUES (:post_id, :user_id)";
        $statement = db_connection()->prepare($sql);
    
        $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        return $statement->execute();
    }
    return false;
}

function remove_like(int $post_id, int $user_id) {
    if (user_liked_post($post_id, $user_id)) {
        $sql = "DELETE FROM likes WHERE post_id=:post_id and user_id=:user_id";
        $statement = db_connection()->prepare($sql);
        $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        return $statement->execute();
    }
    return false;
}

?>