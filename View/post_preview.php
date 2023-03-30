<?php
    require_once("../Controller/post_preview.php");

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Post Preview</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="../js/post_preview.js"> </script>
    </head>

    <body>
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
       <p> Number of like : <?= $nb_like ?> </p>
    </body>
</html>