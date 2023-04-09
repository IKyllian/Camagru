<?php
    require_once(__DIR__.'/log_check.php');
    require_once(__DIR__.'/redirect.php');
    require_once(__DIR__.'/../Model/user_sql.php');
    require_once(__DIR__.'/parse.php');

    if (is_datas_set($_POST, array('img_data', 'filters'))) { 
        $filters_path = json_decode($_POST['filters']);
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
        
        if ($mime_type === 'image/jpeg') {
            $img_name = bin2hex(random_bytes(16)) . ".jpeg";
            $img_path = "../public/pictures/{$img_name}";

            $img_created = imagecreatefromstring($img_datas);

            if ($filters_path) {
                foreach ($filters_path as $item) {
                    $filter_path = __DIR__."/.".$item;
                    if (file_exists($filter_path)) {
                        $filter_mime_type = mime_content_type($filter_path);
                        if ($filter_mime_type === 'image/png') {
                            $img_filter = imagecreatefrompng($filter_path);
                            list($width, $height) = getimagesize($filter_path);
                            imagecopy($img_created, $img_filter, 10, 10, 0, 0, $width, $height);
                        }
                    }
                }
            }
            imagejpeg($img_created, $img_path);
            imagedestroy($img_created);

            if (create_user_img($_SESSION['id'], $img_path))
                echo "./public/pictures/{$img_name}";
            else
                echo "error";
        } else
            echo "error";
    }  
    else
        echo "error";
?>