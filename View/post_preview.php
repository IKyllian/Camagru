<?php
    require_once(__DIR__."/../Controller/post_preview.php");
?>

<?php if (!$post): ?>
<?php require_once(__DIR__."/404.php") ?>
<?php else: ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Post Preview</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="module" src="../js/post_preview.js"> </script>
    </head>

    <body>
        <?php require_once(__DIR__.'/header.php') ?>
       <img src="<?= $post['picture_path'] ?>" />
       <?php if (count($comments) > 0): ?>
            <ul>
                <?php foreach($comments as $comment): ?>
                    <li> <?= $comment['content'] ?> </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p> No Comments yet </p>
        <?php endif; ?>
        <?php if ($is_post_liked): ?>
            <button id="btn-unlike" post-id="<?= $post['post_id'] ?>"> unlike </button>
        <?php else: ?>
            <button id="btn-like" post-id="<?= $post['post_id'] ?>"> like </button>
        <?php endif; ?>
       <p id="nb_like"><?= $nb_like ?></p>
       <form id="comment-form">
            <input type="hidden" name="post-id" value="<?= $post['post_id'] ?>" />
            <input type="text" name="comment" />
            <button formmethod="post"> Envoyer </button>
        </form>
    </body>
</html>
<?php endif; ?>