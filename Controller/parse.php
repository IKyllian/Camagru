<?php
    function string_parse(string $data) {
        $data = trim($data);
        $data = strip_tags($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function is_datas_set(array $datas) {
        foreach ($datas as $data) {
            if (!isset($data) || empty($data))
                return 0;
        }
        return 1;
    }

    // function int_parse(int $) {

    // }
    
    
?>