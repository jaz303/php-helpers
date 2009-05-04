<?php
/**
 * Array URL encode
 */
function query_string_for($array, $omit = null) {
    $out = array();
    _query_string_for_recurse($array, $out, $omit, '');
    return implode('&', $out);
}

function _query_string_for_recurse($src, &$dst, $omit, $prefix) {
    foreach ($src as $k => $v) {
        if ($k === $omit) continue;
        $name = strlen($prefix) ? "{$prefix}[$k]" : $k;
        if (is_enumerable($v)) {
            _query_string_for_recurse($v, $dst, $omit, $name);
        } else {
            $dst[] = urlencode($name) . '=' . urlencode($v);
        }
    }
}

?>