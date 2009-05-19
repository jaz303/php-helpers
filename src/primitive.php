<?php
//
// Primitive helpers

/**
 * Convert value to integer or null
 *
 * @param $i value to make into an integer
 * @return null if $i is null, intval($i) otherwise
 */
function int_or_null($i) {
    return $i === null ? null : (int) $i;
}

/**
 * Trim a string then constrain its length.
 *
 * @param $str string
 * @param $len max length
 * @return string, trimmed then reduced to length $len
 */
function trim_to($str, $len) {
    return substr(trim($str), 0, $len);
}

/**
 * Trim a string, optionally constrain to a given length, or return null
 *
 * @param $str string
 * @param $len optional max length
 * @return null if $str is null, trimmed string with maximal length $len otherwise.
 */
function trim_or_null($str, $len = null) {
    if ($str === null) return null;
    return trim_to($str, $len ? $len : strlen($str));
}

/**
 * Trim a string, optional constrain to a given length, returning the modified string,
 * or null if the resultant string is empty.
 *
 * @param $str string
 * @param $len optional max length
 * @return $str, trimmed and constrained. Returns null if $str is empty after processing.
 */
function trim_to_null($str, $len = null) {
    $str = trim_to($str, $len ? $len : strlen($str));
    return strlen($str) ? $str : null;
}
?>