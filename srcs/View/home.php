<?php
    require_once(__DIR__.'/../Controller/log_check.php');

    $path = "../public/filters/";
    $filters = array_diff(scandir($path), array('.', '..'));

    $status_img = isset($_SESSION['uploaded']) ? $_SESSION['uploaded'] : NULL;
    unset($_SESSION['uploaded']);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <script type="module" src="../js/index.js"> </script>
        <script type="module" src="../js/filters_actions.js"> </script>
        <link rel="shortcut icon" href="#" />
        <script src="https://kit.fontawesome.com/f9678243a8.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="../css/home.css" />
        <link rel="stylesheet" href="../css/notif.css" />
    </head>

    <body>
        <?php require_once(__DIR__.'/header.php') ?>
        <?php if ($status_img): ?>
            <p> <?php echo $status_img ?>  </p>
        <?php endif; ?>
        <div class="page-container">
            <div class="page-wrapper">
                <div class="notif-container notif-error" id="notif-wrapper">
                    <div class="notif-icon notif-icon-error">
                        <i class="fas fa-circle-exclamation"></i>
                    </div>
                    <p> Camera is not available </p>
                    <i class="fas fa-xmark" id="notif-icon-delete"></i>
                </div>
                <div class="camera-container">
                    <div class="filters-container">
                        <?php
                            $i = 0;
                            foreach($filters as $filter):
                        ?>
                            <img class="filter-btn" id="filter-<?=$i?>" src="<?= $path . $filter ?>" width="100" height="100" />
                            <?php $i++ ?>
                        <?php endforeach; ?>
                    </div>
                    <div>
                        <div class="display-container" id="cam-container">
                            <video id="video">Video stream not available.</video>
                            <canvas id="canvas" width="640" height="480"> </canvas>
                            <img id="photo" alt="" />
                        </div>
                    </div>
                    <div class="camera-buttons">
                        <form id="form-file" method="#">
                            <label id="input-label">
                                Upload
                                <i class="fas fa-upload"></i>
                                <input id="input-file" type="file" name="file" accept="image/gif, image/jpg, image/jpeg, image/png" />
                            </label>
                            
                            <button id="remove-file"> Remove file </button>
                        </form>

                        <div id="btn-save-container">
                            <button id="delete-post"> 
                                <i class="fas fa-xmark"></i>
                            </button> 
                            <button id="save-post">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                        <button id="btn-shoot" disabled>
                            <i class="fas fa-camera"></i>
                        </button>
                        <button id="btn-cam"> Activate Camera </button>
                    </div>
                </div>
                <div class="pictures-wrapper">
                    <p> Pictures taken </p>
                    <ul id="picture-preview"> </ul>
                </div>
            </div>
        </div>
        <?php require_once(__DIR__.'/footer.html') ?>
    </body>
</html>