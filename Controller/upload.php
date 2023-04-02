<?php
    require_once(__DIR__.'/log_check.php');
    require_once(__DIR__.'/redirect.php');
    require_once(__DIR__.'/../Model/user_sql.php');

    if (isset($_POST['img_data']) && isset($_POST['img_id'])) {
        // $_SESSION['uploaded'] = "IMG uploaded";
        $img_datas = $_POST['img_data'];
        $img_id = $_POST['img_id'];

        $img_datas= str_replace("data:image/jpeg;base64,","",$img_datas);
        $img_datas = base64_decode($img_datas);

        // if (filesize($img_datas) <= 0) {
        //     echo ('Uploaded file has no contents.');
        //     return ;
        // }

        $file_infos = finfo_open();
        $mime_type = finfo_buffer($file_infos, $img_datas, FILEINFO_MIME_TYPE);
        
        if ($mime_type == 'image/jpeg') {
            $img_name = bin2hex(random_bytes(16)) . ".jpeg";
            $img_path = "../public/pictures/{$img_name}";

            $img_created = imagecreatefromstring($img_datas);
            $img_filter = imagecreatefrompng('../public/filters/5a28b41a3f4334.5103353815126169862591.png');

            imagecopymerge($img_created, $img_filter, 10, 10, 0, 0, 500, 200, 75);
            
            imagejpeg($img_created, $img_path);
            imagedestroy($img_created);

            if (create_user_img($_SESSION['id'], $img_path))
                echo "TRUE";
            else
                echo "FALSE ON PDO EXEC";
        } else
            echo "FALSE WRONG MIME TYPE";
    }  
    else
        echo "FALSE NO DATAS";
?>