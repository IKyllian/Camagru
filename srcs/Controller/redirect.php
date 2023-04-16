<?php
    function redirect_to(string $url, string $var_name = NULL, string $error = NULL) {
        if ($error != NULL && $var_name != NULL)
            $_SESSION["{$var_name}"] = $error;
        header("Location: {$url}");
        exit;
    }
?>