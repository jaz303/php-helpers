<?php
/**
 * Generate a query string from an array, optionally replacing and/or removing
 * elements from the array (referenced by path).
 *
 * @param $array array to turn into a query string
 * @param $replace map of paths to replace, and their new values
 * @param $remvoe array of paths to remove
 */
function query_string(array $array, $replace = null, $remove = null) {
    if ($replace !== null) {
        foreach ((array) $replace as $path => $v) {
            array_path_replace($array, $path, $v);
        }
    }
    if ($remove !== null) {
        foreach ((array) $remove as $path) {
            array_path_unset($array, $path);
        }
    }
    return array_url_encode($array);
}

/**
 * Generate a query string fragment.
 *
 * one string param  - returned as-is
 * one array param   - passed to array_url_encode() and returned
 * two string params - returns $arg1=$arg2
 *
 * @param $arg1
 * @param $arg2
 * @return query string fragment
 */
function query_string_fragment($k, $v = null) {
    if ($v === null) {
        if (is_array($k)) {
            return array_url_encode($k);
        } else {
            return (string) $k;
        }
    } else {
        return urlencode($k) . '=' . urlencode($v);
    }
}

function url_append($url, $k, $v = null) {
    $sep = (strpos($url, '?') === false) ? '?' : '&';
    return $url . $sep . query_string_fragment($k, $v);
}

function query_string_append($query_string, $k, $v = null) {
    return $query_string . (strlen($query_string) ? '&' : '') . query_string_fragment($k, $v);
}

function array_url_encode($array, $omit = null) {
    $out = array();
    _array_url_encode_recurse($array, $out, $omit, '');
    return implode('&', $out);
}

function _array_url_encode_recurse($src, &$dst, $omit, $prefix) {
    foreach ($src as $k => $v) {
        if ($k === $omit) continue;
        $name = strlen($prefix) ? "{$prefix}[$k]" : $k;
        if (is_enumerable($v)) {
            _array_url_encode_recurse($v, $dst, $omit, $name);
        } else {
            $dst[] = urlencode($name) . '=' . urlencode($v);
        }
    }
}

?>