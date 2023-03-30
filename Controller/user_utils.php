<?php

function is_user_active($user)
{
    return (int)$user['active'] === 1;
}

?>