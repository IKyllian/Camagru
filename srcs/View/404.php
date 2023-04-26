<?php
    require_once(__DIR__."/../Controller/log_check.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Page not found</title>
        <link rel="shortcut icon" href="#" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/index.css">
        <style>
            h1 {
                text-align: center;
                color: #3C486B;
            }
        </style>
    </head>

    <body>
        <?php require_once(__DIR__."/header.html") ?>
        <div class="page-container">
            <h1>404 NOT FOUND</h1>
        </div>
        <?php require_once(__DIR__.'/footer.html') ?>
    </body>
</html>