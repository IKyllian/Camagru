<?php
    session_start();
    // require 'setup.php';
    if (!isset($_SESSION['logged'])) {
        header("Location: /View/login.php");
        exit ;
    }

    $status_img = isset($_SESSION['uploaded']) ? $_SESSION['uploaded'] : NULL;
    unset($_SESSION['uploaded']);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <script src="./js/index.js"> </script>
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

        <?php if (!$status_img): ?>
            <p> Not upload </p>
        <?php endif; ?>

        <div>
            <video id="video">Video stream not available.</video>
            <button id="startbutton">Take photo</button>
        </div>

        <canvas id="canvas"> </canvas>
            <div class="output">
            <img id="photo" alt="The screen capture will appear in this box." />
        </div>

        <button id="save"> save </button> 

    </body>
</html>

   

