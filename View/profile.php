<?php
    require_once(__DIR__."/../Controller/profile.php");

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Profile</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/profile.css">
        <link rel="stylesheet" href="../css/gallery.css">
        <script src="https://kit.fontawesome.com/f9678243a8.js" crossorigin="anonymous"></script>
        <script type="module" src="../js/profile.js"> </script>
    </head>

    <body>
        <?php require_once(__DIR__.'/header.html') ?>
        <div class="page-container">
            <div class="profile-wrapper">
                <?php if ($is_user_connected): ?>
                    <div class="profile-container">
                        <p class="infos-title"> Informations personelles</p>
                        <div class="infos-wrapper">
                            <div class="info-item">
                                <span class="field"> Username: </span>
                                <span> <?= $user['username'] ?> </span>
                            </div>
                            <div class="info-item">
                                <span class="field"> Email: </span>
                                <span> <?= $user['email'] ?> </span>
                            </div>
                            <div class="info-item">
                                <span class="field"> Notification: </span>
                                <span> <?= $user['active_notif'] ? "Activée" : "Désactivée" ?> </span>
                            </div>
                            <div class="info-item">
                                <span class="field"> Created at: </span>
                                <span> <?= $date->format('d-m-Y') ?> </span>
                            </div>
                        </div>
                        <div class="buttons-wrapper">
                            <a href="/View/edit_password.php"> Reset Password </a>
                            <a href="/View/edit_profile.php"> Edit profile </a>
                        </div>
                    </div>
                <?php endif; ?>
                <h2> <?=$user['username']?>'s Posts </h2>
                <?php if (count($user_posts) > 0): ?>
                    <div class="gallery-container">
                        <?php foreach($user_posts as $post): ?>
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
                                <a href="/View/profile.php?id=<?=$user_id?>&page=<?=$idx?>"> <?= $idx ?> </a>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                <?php else: ?>
                    <p> No post yet </p>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>