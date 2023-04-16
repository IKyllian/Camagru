<?php

require_once(__DIR__."/../Model/user_sql.php");

// Need to sanitize
$user = find_unverified_user($_GET['activation_code'], $_GET['email']);

// if user exists and activate the user successfully
if ($user && activate_user($user['user_id'])) {
    header("Location: /View/login.php");
    exit;
}

header("Location: /View/register.php");
exit;

?>