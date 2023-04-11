<?php
    require_once(__DIR__.'/Controller/log_check.php');
    // require 'setup.php';

    $path = "./public/filters/";
    $filters = array_diff(scandir($path), array('.', '..'));

    $status_img = isset($_SESSION['uploaded']) ? $_SESSION['uploaded'] : NULL;
    unset($_SESSION['uploaded']);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <script type="module" src="./js/index.js"> </script>
        <link rel="shortcut icon" href="#" />
        <style>
            canvas {
                display: none;
            }

            #btn-save-container {
                display: none;
            }
            .display-container {
                position: relative;
                width: 640px;
                height: 480px;
            }
            .display-container > video {
                position: absolute;
            }
            .display-container > img {
                position: absolute;
            }

            #remove-file {
                display: none;
            }
        </style>
    </head>

    <body>
        <?php require_once(__DIR__.'/View/header.php') ?>
        <?php if ($status_img): ?>
            <p> <?php echo $status_img ?>  </p>
        <?php endif; ?>
            
        <?php foreach($filters as $filter): ?>
            <img class="filter-btn" src="<?= $path . $filter ?>" width="100" height="100" />
        <?php endforeach; ?>

        <form id="form-file" method="#">
            <input id="input-file" type="file" name="file" />
            <button id="remove-file"> Remove file </button>
        </form>
        
        <div>
            <div>
                <div class="display-container">
                    <video id="video">Video stream not available.</video>
                    <canvas id="canvas" width="640" height="480"> </canvas>
                    <img id="photo" alt="" />
                </div>
            </div>
            <button id="btn-shoot" disabled>Take photo</button>
        </div>
        <button id="btn-cam"> Activate Camera </button>

        <ul id="picture-preview"> </ul>
        <div id="btn-save-container">
            <button id="save"> Save </button>
            <button id="delete"> Delete </button> 
        </div>
       
    </body>
</html>

   

