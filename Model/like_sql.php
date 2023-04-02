<?php
require_once(__DIR__.'/user_sql.php');
require_once(__DIR__.'/../connection.php');

function get_like_number(int $post_id) {
    $sql = "SELECT COUNT(*) FROM likes WHERE post_id={$post_id}";
    $statement = db_connection()->query($sql);
    return $statement->fetchColumn();
}

function add_like(int $post_id, int $user_id) {
    if (!user_liked_post($post_id, $user_id)) {
        $sql = "INSERT INTO likes (post_id, user_id) VALUES (:post_id, :user_id)";
        $statement = db_connection()->prepare($sql);
    
        $data = [
            ':post_id' => $post_id,
            ':user_id' => $user_id,
        ];
        return $statement->execute($data);
    }
    return false;
}

function remove_like(int $post_id, int $user_id) {
    if (user_liked_post($post_id, $user_id)) {
        $sql = "DELETE FROM likes WHERE post_id=:post_id and user_id=:user_id";
        $statement = db_connection()->prepare($sql);
    
        $data = [
            ':post_id' => $post_id,
            ':user_id' => $user_id,
        ];
    
        return $statement->execute($data);
    }
    return false;
}

?>