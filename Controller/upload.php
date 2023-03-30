<?php
    require_once('./redirect.php');
    require_once('../Model/user_sql.php');
    session_start();

    if (!isset($_SESSION['id']))
        redirect_to("/View/login.php");
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