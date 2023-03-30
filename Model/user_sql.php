<?php

require_once('../connection.php');
require_once('../Controller/user_utils.php');
require_once('../Controller/redirect.php');

function find_user($fields, $condition_field, $condition_value) {
    $fields_to_string = implode(", ", $fields);
    $stmt = db_connection()->prepare("SELECT {$fields_to_string} FROM users WHERE {$condition_field} = :{$condition_field}");
    $result = $stmt->execute([":{$condition_field}" => $condition_value]);
    if (false === $result) {
        throw new Exception(implode(' ', $stmt->errorInfo()));
    }
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function user_signin(string $username, string $password) {
    $result = find_user(array("user_id, password, active"), "username", $username);
    if ($result !== false) {
        if (password_verify($password, $result['password'])) {
            if (is_user_active($result)) {
                session_regenerate_id();
                $_SESSION['logged'] = TRUE;
                $_SESSION['name'] = $username;
                $_SESSION['id'] = $result['user_id'];
                redirect_to("/index.php");
            } else {
                redirect_to("/View/login.php", "error_msg", "Account not activated");
            }
        } else {
            redirect_to("/View/login.php", "error_msg", "Password incorrect");
        }
    } else {
        redirect_to("/View/login.php", "error_msg", "Username incorrect");
    }
}

function user_register(string $username, string $email, string $password, string $activation_code, int $expiry = 1 * 24 * 60 * 60) {
    $statement = db_connection()->prepare(" INSERT
                                            INTO users (username, email, password, activation_code, activation_expiry)
                                            VALUES (:username, :email, :password, :activation_code, :activation_expiry)");
    $data = [
        ':username' => $username,
        ':email' => $email,
        ':password' => password_hash($password, PASSWORD_DEFAULT),
        ':activation_code' => password_hash($activation_code, PASSWORD_DEFAULT),
        ':activation_expiry' => date('Y-m-d H:i:s', time() + $expiry),
    ];
    return $statement->execute($data);

}

function delete_user_by_id(int $id, int $active = 0)
{
    $sql = 'DELETE FROM users
            WHERE user_id=:id and active=:active';

    $statement = db_connection()->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->bindValue(':active', $active, PDO::PARAM_INT);

    return $statement->execute();
}

function find_unverified_user(string $activation_code, string $email)
{
    $sql = 'SELECT user_id, activation_code, activation_expiry < now() as expired
            FROM users
            WHERE active = 0 AND email=:email';

    $statement = db_connection()->prepare($sql);

    $statement->bindValue(':email', $email);
    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Already expired, delete the in active user with expired activation code
        if ((int)$user['expired'] === 1) {
            delete_user_by_id($user['id']);
            return null;
        }
        
        if (password_verify($activation_code, $user['activation_code'])) {
            return $user;
        }
    }

    return null;
}

function activate_user(int $user_id): bool
{
    $sql = 'UPDATE users
            SET active = 1,
                activated_at = CURRENT_TIMESTAMP
            WHERE user_id=:id';

    $statement = db_connection()->prepare($sql);
    $statement->bindValue(':id', $user_id, PDO::PARAM_INT);

    return $statement->execute();
}

function create_user_img(int $user_id, string $path) {
    $sql = "INSERT INTO posts (picture_path, user_id) VALUES (:picture_path, :user_id)";

    $statement = db_connection()->prepare($sql);

    $data = [
        ':picture_path' => $path,
        ':user_id' => $user_id,
    ];

    return $statement->execute($data);
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

function get_post_by_id(int $post_id) {
    $sql = "SELECT posts.*, users.username
            FROM posts
            INNER JOIN users
            ON posts.user_id=users.user_id
            WHERE post_id=:post_id";

    $statement = db_connection()->prepare($sql);
    $data = [
        ':post_id' => $post_id,
    ];
    $statement->execute($data);

    return $statement->fetch();
}

function user_liked_post(int $post_id, int $user_id) {
    $sql = "SELECT * FROM likes WHERE post_id=:post_id and user_id=:user_id";

    $statement = db_connection()->prepare($sql);
    $data = [
        ':post_id' => $post_id,
        ':user_id' => $user_id,
    ];
    $statement->execute($data);

    if ($statement->fetchColumn())
        return true;
    else
        return false;
}

function get_comment_number(int $post_id) {
    $sql = "SELECT COUNT(*) FROM comments WHERE post_id={$post_id}";
    $statement = db_connection()->query($sql);
    return $statement->fetchColumn();
}

function get_like_number(int $post_id) {
    $sql = "SELECT COUNT(*) FROM likes WHERE post_id={$post_id}";
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

function add_like(int $post_id, int $user_id) {
    $sql = "INSERT INTO likes (post_id, user_id) VALUES (:post_id, :user_id)";
    $statement = db_connection()->prepare($sql);

    $data = [
        ':post_id' => $post_id,
        ':user_id' => $user_id,
    ];

    return $statement->execute($data);
}

function remove_like(int $post_id, int $user_id) {
    $sql = "DELETE FROM likes WHERE post_id=:post_id and user_id=:user_id";
    $statement = db_connection()->prepare($sql);

    $data = [
        ':post_id' => $post_id,
        ':user_id' => $user_id,
    ];

    return $statement->execute($data);
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

