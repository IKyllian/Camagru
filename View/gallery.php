<?php
    require_once(__DIR__."/../Controller/gallery.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Gallery</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="module" src="../js/gallery.js"> </script>
    </head>

    <body>
        <?php require_once(__DIR__.'/header.php') ?>
        <?php if ($req_error): ?>
            <p> Error while request </p>
        <?php endif; ?>

        <?php foreach($posts as $post): ?>
            <div id="post-<?= $post->post_data['post_id']?>">
                <p> <?= $post->post_data['username'] ?> </p>
                <a href="post_preview.php?post_id=<?= $post->post_data['post_id']?>" >
                    <img src="<?= $post->post_data['picture_path'] ?>" />
                    <div>
                        <span> comments: <?= $post->nb_comments ?> </span>
                        <span> like: <?= $post->nb_likes ?> </span>
                    </div>
                </a>
                <?php if ($post->post_data['user_id'] === $_SESSION['id']): ?>
                    <button class='delete-btn' post-id="<?= $post->post_data['post_id']?>"> Delete post </button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </body>
</html>