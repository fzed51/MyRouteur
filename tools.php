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

if (!function_exists('concatPath')) {

    function concatPath($debut, $fin, $separator = '/') {
        $path = preg_replace("/[\\/\\\\]+(?:.[\\/\\\\]+)*/", $separator, $debut . $separator . $fin);
        return $path;
    }

}

if (!function_exists('string2Html')) {

    function string2Html($string) {
        $string_array = explode(PHP_EOL, $string);
        foreach ($string_array as $key => $value) {
            $string_array[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');
        }
        return implode('<br>' . PHP_EOL, $string_array);
    }

}
