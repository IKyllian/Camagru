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
        <style>
            canvas {
                display: none;
            }
        </style>
    </head>

    <body>
        <h1> Hello </h1>
        <a href="./View/gallery.php"> Gallery </a>
        <a href="./Controller/logout.php"> Logout </a>
        <?php if ($status_img): ?>
            <p> <?php echo $status_img ?>  </p>
        <?php endif; ?>

        <?php foreach($filters as $filter): ?>
            <img src="<?= $path . $filter ?>" width="100" height="100" />
        <?php endforeach; ?>

        <form id="form-file">
            <input type="file" name="file"  />
        </form>
        
        <button id="btn-cam"> Activate Camera </button>
        <div>
            <video id="video">Video stream not available.</video>
            <button id="btn-shoot">Take photo</button>
        </div>

        <canvas id="canvas" width="640" height="480"> </canvas>
            <div class="output">
            <img id="photo" alt="The screen capture will appear in this box." />
        </div>

        <button id="save"> save </button> 

    </body>
</html>

   

