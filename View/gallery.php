<?php
    require_once(__DIR__."/../Controller/gallery.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Gallery</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/gallery.css">
        <script src="https://kit.fontawesome.com/f9678243a8.js" crossorigin="anonymous"></script>
        <script type="module" src="../js/gallery.js"> </script>
    </head>

    <body>
        <?php require_once(__DIR__.'/header.php') ?>
        <!-- <?php if ($req_error): ?>
            <p> Error while request </p>
        <?php endif; ?> -->
            
        <div class="page-container">
            <div class="gallery-wrapper">
                <div class="gallery-container">
                    <?php foreach($posts as $post): ?>
                        <div class="post-item" id="post-<?= $post->post_data['post_id']?>">
                            <a href="post_preview.php?post_id=<?= $post->post_data['post_id']?>" >
                                <img src="<?= $post->post_data['picture_path'] ?>" />
                                <div class="hover-post-background"> </div>
                                <div class="post-infos">
                                    <div>
                                        <i class="fas fa-heart fa-lg"></i>
                                        <span> <?= $post->nb_likes ?>  </span>
                                    </div>
                                    <div>
                                        <i class="fas fa-comment fa-lg"></i>
                                        <span> <?= $post->nb_comments ?>  </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </body>
</html>