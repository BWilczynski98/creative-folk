<?php

function dd(...$vals)
{
    foreach ($vals as $val) {
        echo "<pre>";
        var_dump($val);
        echo "</pre>";
    }
    die();
}

function abort($code = 404)
{
    http_response_code($code);
    include "../public/404.php";
    die();
}