<?php
    require_once("../Controller/gallery.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Gallery</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <?php if ($req_error): ?>
            <p> Error while request </p>
        <?php endif; ?>

        <?php foreach($posts as $post): ?>
            <p> <?= $post['username'] ?> </p>
            <a href="post_preview.php?post_id=<?= $post['post_id']?>" >
                <img src="<?= $post['picture_path'] ?>" />
                <div>
                    <span> comments: <?= $count_comment ?> </span>
                    <span> like: <?= $count_like ?> </span>
                </div>
               
            </a>

            <!-- <form class="comment-form" name="comment-form">
                <input type="hidden" name="post-id" value="<?= $post['post_id'] ?>" />
                <input type="text" name="comment" />
                <button formmethod="post"> Envoyer </button>
            </form> -->
        <?php endforeach; ?>
    </body>
</html>