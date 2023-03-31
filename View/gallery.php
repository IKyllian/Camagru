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
            <p> <?= $post->post_data['username'] ?> </p>
            <a href="post_preview.php?post_id=<?= $post->post_data['post_id']?>" >
                <img src="<?= $post->post_data['picture_path'] ?>" />
                <div>
                    <span> comments: <?= $post->nb_comments ?> </span>
                    <span> like: <?= $post->nb_likes ?> </span>
                </div>
            </a>
        <?php endforeach; ?>
    </body>
</html>