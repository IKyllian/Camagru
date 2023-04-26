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

    function password_regex_check(string $password) {
        $password_regex = "/^(?=.*?[A-Z])(?=.*?[0-9]).{5,}$/";
        return preg_match($password_regex, $password);
    }

    function username_check(string $username) {
        if (strlen($username) > 1)
            return 1;
        return 0;
    }
    
?>