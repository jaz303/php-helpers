<?php
//
// Array paths (docs coming soon)

function array_path($array, $path, $default = null) {
    $path = explode('.', $path);
    while (count($path)) {
        $key = array_shift($path);
        if (!isset($array[$key])) return $default;
        $array = $array[$key];
    }
    return $array;
}

function array_path_unset(&$array, $path) {
    $tmp = & $array;
    $path = explode('.', $path);
    while (count($path) > 1) {
        $key = array_shift($path);
        if (!isset($tmp[$key])) return;
        $tmp = & $tmp[$key];
    }
    unset($tmp[array_shift($path)]);
}

function array_without_path($array) {
    $args = func_get_args();
    array_shift($args);
    foreach ($args as $path) array_path_unset($array, $path);
    return $array;
}

function array_path_replace(&$array, $path, $value) {
    $tmp = & $array;
    $path = explode('.', $path);
    while (count($path) > 1) {
        $key = array_shift($path);
        if (!isset($tmp[$key])) $tmp[$key] = array();
        $tmp = & $tmp[$key];
    }
    $tmp[array_shift($path)] = $value;
}

function array_path_to_name($path) {
    $bits = explode('.', $path);
    $out  = array_shift($bits);
    while (count($bits)) $out .= '[' . array_shift($bits) . ']';
    return $out;
}

?>