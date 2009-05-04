<?php
/**
 * Array URL encode
 */
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