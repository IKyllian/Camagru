<?php
    function string_parse(string $data) {
        $data = trim($data);
        $data = strip_tags($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    function is_datas_set(array $datas, array $keys) {
        foreach ($keys as $key) {
            if (!isset($datas[$key]) || empty($datas[$key]))
                return 0;
        }
        return 1;
    }    
    
?>