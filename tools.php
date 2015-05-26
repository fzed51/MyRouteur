<?php

if (!function_exists('dd')) {

    function dd($var) {
        var_dump($var);
        die();
    }

}

if (!function_exists('include_if_exist')) {

    function include_if_exist($filename) {
        if (file_exists($filename)) {
            include $filename;
        }
    }

}