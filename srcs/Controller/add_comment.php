<?php
    require_once(__DIR__.'/log_check.php');
    require_once(__DIR__.'/redirect.php');
    require_once(__DIR__.'/mail.php');
    require_once(__DIR__.'/../Model/user_sql.php');
    require_once(__DIR__.'/../Model/comment_sql.php');
    require_once(__DIR__.'/../Model/post_sql.php');
    require_once(__DIR__.'/parse.php');

    function send_post_mail(string $email, string $owner, string $sender, string $comment, $img_path)
    {
        $subject = 'New comment received';
        $message = "
        <html>
            <head>
                <title>HTML email</title>
            </head>
            <body>
                <p> Hi $owner ! </p>
                <p> Your post received a new comment ! </p>
                <img src='cid:post_img' width='540' height='380' />
                <p> $sender : <span> $comment </span> </p>
            </body>
        </html>";

        if (sendmail($email, $subject, $message, $img_path))
            return 1;
        return 0;
    }

    if (is_datas_set($_POST, array('comment', 'post_id'))) {
        $comment = string_parse($_POST['comment']);
        if (is_numeric($_POST['post_id'])) {
            $post_id = $_POST['post_id'];
            $post = get_post_by_id($post_id);
            $post_owner = find_user(array("active_notif, email, username"), "user_id", $post['user_id']);
            if ($post != false && find_user(array('user_id'), 'user_id', $logged_user_id) != false) {
                $new_comment = create_comment($post_id, $logged_user_id, $comment);
                if ($new_comment) {
                    echo json_encode(array('status' => true, 'comment' => $new_comment));
                    if ($post_owner && $logged_user_username && $post_owner['active_notif']) {
                        send_post_mail($post_owner['email'], $post_owner['username'], $logged_user_username, $comment, $post['picture_path']);
                    }
                }
                else
                    echo json_encode(array('status' => false));
            }
        } else
            echo json_encode(array('status' => false));
    }
?>