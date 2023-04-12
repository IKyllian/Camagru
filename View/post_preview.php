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
        <script src="https://kit.fontawesome.com/f9678243a8.js" crossorigin="anonymous"></script>
        <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/post_preview.css">
    </head>

    <body>
        <?php require_once(__DIR__.'/header.php') ?>
        <div class="page-container">
            <div class="preview-wrapper">
                <div class="preview-container">
                    <div class="img-wrapper">
                        <img src="<?= $post['picture_path'] ?>" />
                    </div>
                    <div class="right-preview">
                        <div class="header-infos">
                            <a href="/View/profile.php?id=<?=$post['user_id']?>"> <?= $post['username'] ?>  </a>
                            <?php if ($post['user_id'] === $_SESSION['id']): ?>
                                <i id="delete-btn" class="fas fa-trash" post-id="<?= $post['post_id']?>"></i>
                            <?php endif; ?>
                        </div>
                        <div class="comment-section">
                            <?php if (count($comments) > 0): ?>
                                <ul>
                                    <?php foreach($comments as $comment): ?>
                                        <li>
                                            <p class="comment-sender"> <?= $comment['username'] ?> </p>
                                            <p class="comment-content"> <?= $comment['content'] ?> </p>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="no-comments">
                                    <p> No Comments yet </p>
                                </div>
                            <?php endif; ?>

                            <div class="likes-container">
                                <?php if ($is_post_liked): ?>
                                    <i id="btn-unlike" class="fas fa-heart fa-lg" post-id="<?= $post['post_id'] ?>"></i>
                                <?php else: ?>
                                    <i id="btn-like" class="far fa-heart fa-lg" post-id="<?= $post['post_id'] ?>"></i>
                                <?php endif; ?>
                                    <p id="nb_like"><?= $nb_like ?></p>
                            </div>

                            <form id="comment-form">
                                <input type="hidden" name="post-id" value="<?= $post['post_id'] ?>" />
                                <input class="comment-input" placeholder="Ajouter un commentaire..." type="text" name="comment" />
                                <button formmethod="post">
                                    <i class="far fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<?php endif; ?>