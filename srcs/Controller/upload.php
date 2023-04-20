<?php
    require_once(__DIR__.'/log_check.php');
    require_once(__DIR__.'/redirect.php');
    require_once(__DIR__.'/../Model/user_sql.php');
    require_once(__DIR__.'/parse.php');

    function resize_filter($filter) {
        $img_filter = imagecreatefrompng($filter['path']);
        list($width, $height) = getimagesize($filter['path']);
        $imageLayer = imagecreatetruecolor($filter['width'], $filter['height']);
        imagesavealpha($imageLayer, true);
        $color = imagecolorallocatealpha($imageLayer, 0, 0, 0, 127);
        imagefill($imageLayer, 0, 0, $color);
        imagecopyresampled($imageLayer, $img_filter, 0, 0, 0, 0, $filter['width'], $filter['height'], $width, $height);
        return $imageLayer;
    }

    if (is_datas_set($_POST, array('img_data', 'filters'))) { 
        $filters_path = json_decode($_POST['filters'], true);
        $img_datas = $_POST['img_data'];

        $img_datas= str_replace("data:image/jpeg;base64,","",$img_datas);
        $img_datas = base64_decode($img_datas);
        
        $file_infos = finfo_open();
        $mime_type = finfo_buffer($file_infos, $img_datas, FILEINFO_MIME_TYPE);
        
        if ($mime_type === 'image/jpeg') {
            $img_name = bin2hex(random_bytes(16)) . ".jpeg";
            $img_path = "../public/pictures/{$img_name}";

            $img_created = imagecreatefromstring($img_datas);

            if ($img_created) {
                if ($filters_path) {
                    foreach ($filters_path as $item) {
                        if (file_exists($item['path'])) {
                            $filter_mime_type = mime_content_type($item['path']);
                            if ($filter_mime_type === 'image/png') {
                                $filter_img = resize_filter($item);
                                imagecopy($img_created, $filter_img, $item['offsetLeft'], $item['offsetTop'], 0, 0, $item['width'], $item['height']);
                            }
                        }
                    }
                }
                imagejpeg($img_created, $img_path);
                imagedestroy($img_created);
    
                if (create_user_img($_SESSION['id'], $img_path))
                    echo json_encode(array('status' => true, 'path' => "../public/pictures/{$img_name}"));
                else
                    echo json_encode(array('status' => false));
            } else
                echo json_encode(array('status' => false));
        } else
            echo json_encode(array('status' => false));
    } else
        echo json_encode(array('status' => false));
?>