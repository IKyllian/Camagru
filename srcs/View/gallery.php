<?php
    require_once(__DIR__."/../Controller/gallery.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Gallery</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="#" />
        <link rel="stylesheet" href="../css/gallery.css">
        <script src="https://kit.fontawesome.com/f9678243a8.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <?php require_once(__DIR__.'/header.html') ?>
        <!-- <?php if ($req_error): ?>
            <p> Error while request </p>
        <?php endif; ?> -->
            
        <div class="page-container">
            <div class="gallery-wrapper">
                <?php if (count($posts) > 0): ?>
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
                    <div class="pagination">
                        <?php for($idx = 1; $idx <= $page_nbr; $idx++): ?>
                            <?php if ($idx == $page): ?>
                                <span> <?= $idx ?> </span>
                            <?php else: ?>
                                <a href="/View/gallery.php?page=<?= $idx ?>"> <?= $idx ?> </a>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                <?php else: ?>
                    <h2> No post yet </h2>
                <?php endif; ?>
            </div>
        </div>
        <?php require_once(__DIR__.'/footer.html') ?>
    </body>
</html>