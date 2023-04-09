<?php

require_once(__DIR__.'/../connection.php');
require_once(__DIR__.'/../Controller/user_utils.php');
require_once(__DIR__.'/../Controller/redirect.php');

function get_condition_type(mixed $condition_value) {
    $type = gettype($condition_value);
    if ($type === "string")
        return PDO::PARAM_STR;
    else if ($type === "integer")
        return PDO::PARAM_INT;
    else if ($type === "boolean")
        return PDO::PARAM_BOOL;
    else
        return null;
}

function check_user_token($user_id, $token) {
    $sql = "SELECT user_id FROM users WHERE user_id=:user_id and token=:token";
    $stmt = db_connection()->prepare($sql);
    $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->bindValue(":token", $token, PDO::PARAM_STR);
    $result = $stmt->execute();
    if (false === $result) {
        throw new Exception(implode(' ', $stmt->errorInfo()));
    }
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function find_user(array $fields, string $condition_field, mixed $condition_value) {
    $condition_type = get_condition_type($condition_value);
    if ($condition_type === null)
        return false;
    $fields_to_string = implode(", ", $fields);
    $sql = "SELECT {$fields_to_string} FROM users WHERE {$condition_field} = :{$condition_field}";
    $stmt = db_connection()->prepare($sql);
    $stmt->bindValue(":{$condition_field}", $condition_value, $condition_type);
    $result = $stmt->execute();
    if (false === $result) {
        throw new Exception(implode(' ', $stmt->errorInfo()));
    }
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function create_user_token($user_id, $new_token) {
    $sql = "UPDATE users SET token=:token WHERE user_id=:user_id";
    $statement = db_connection()->prepare($sql);
    $statement->bindValue(':token', $new_token, PDO::PARAM_STR);
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    return $statement->execute();
}

function user_signin(string $username, string $password) {
    $result = find_user(array("user_id, password, active"), "username", $username);
    if ($result !== false) {
        if (password_verify($password, $result['password'])) {
            if (is_user_active($result)) {
                $new_token = hash("sha256", bin2hex(random_bytes(16))); 
                create_user_token($result['user_id'], $new_token);
                session_regenerate_id();
                $_SESSION['logged'] = TRUE;
                $_SESSION['name'] = $username;
                $_SESSION['token'] = $new_token;
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

    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->bindValue(':email', strtolower($email), PDO::PARAM_STR);
    $statement->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
    $statement->bindValue(':activation_code', password_hash($activation_code, PASSWORD_DEFAULT), PDO::PARAM_STR);
    $statement->bindValue(':activation_expiry', date('Y-m-d H:i:s', time() + $expiry), PDO::PARAM_STR);

    return $statement->execute();
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

    $statement->bindValue(':email', $email, PDO::PARAM_STR);
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

    $statement->bindValue(':picture_path', $path, PDO::PARAM_STR);
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);

    return $statement->execute();
}

function user_liked_post(int $post_id, int $user_id) {
    $sql = "SELECT * FROM likes WHERE post_id=:post_id and user_id=:user_id";

    $statement = db_connection()->prepare($sql);
    $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $statement->execute();

    if ($statement->rowCount() > 0)
        return true;
    else
        return false;
}

function change_user_field(int $user_id, string $field, string $field_value) {
    $field_type = get_condition_type($field_value);
    if ($field_type === null)
        return false;
    $user = find_user(array("user_id, {$field}"), "user_id", $user_id);

    if (!$user || ($user && $user["{$field}"] == $field_value))
        return false;

    $sql = "UPDATE users SET {$field}=:{$field} WHERE user_id=:user_id";
    
    $statement = db_connection()->prepare($sql);
    $statement->bindValue(":{$field}", $field_value, $field_type);
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    return $statement->execute();
}

?>