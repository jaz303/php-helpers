<?php
/**
 * php-helpers
 * (c) 2009 Jason Frame [jason@onehackoranother.com]
 */

//
// These constants define the locations of your site's static assets.
// Define your own prior to inclusion of this lib or accept the defaults below.
// STATIC_ROOT/ will be prepended to each asset directory when generating
// asset paths.

if (!defined('STATIC_ROOT'))        define('STATIC_ROOT', '');
if (!defined('STATIC_JS_DIR'))      define('STATIC_JS_DIR', 'javascripts');
if (!defined('STATIC_IMAGE_DIR'))   define('STATIC_IMAGE_DIR', 'images');
if (!defined('STATIC_CSS_DIR'))     define('STATIC_CSS_DIR', 'stylesheets');

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
//
// Support

/**
 * Parse a selector of the form #foo.bar.baz into constituent ID and classes.
 * An array argument will be returned unchanged.
 */
function parse_simple_selector($s) {
    if (!is_array($s)) {
        preg_match('/^(#([\w-]+))?((\.[\w-]+)*)$/', $s, $matches);
        $s = array();
        if (!empty($matches[2])) $s['id'] = $matches[2];
        if (!empty($matches[3])) $s['class'] = trim(str_replace('.', ' ', $matches[3]));
    }
    return $s;
}

/**
 * Turn some representation of a URL into a string.
 * Scalar parameters are coerced to strings and returned.
 * Any other argument will be passed to url_for(), which should be implemented by
 * your application.
 */
function generate_url($u) {
    return is_scalar($u) ? (string) $u : url_for($u);
}

function is_enumerable($thing) {
    return is_array($thing) || is_object($thing);
}

//
// Array paths (docs coming soon)

function array_path($array, $path, $default = null) {
    $path = explode('.', $path);
    while ($key = array_shift($path)) {
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

//
// Asset

/**
 * Returns the URL for static asset $what
 *
 * If $what is an absolute URI/path, or a relative path starting with "./",
 * it is returned unmodified. Otherwise, a static asset URL is generated based
 * on STATIC_ROOT.
 */
function url_for_asset($what, $where) {
    if (preg_match('%^(https?://|\.?/)%', $what)) {
        return $what;
    } else {
        return STATIC_ROOT . "/$where/$what";
    }
}

function url_for_image($image) {
    return url_for_asset($image, STATIC_IMAGE_DIR);
}

function url_for_stylesheet($stylesheet) {
    return url_for_asset($stylesheet, STATIC_CSS_DIR);
}

function url_for_javascript($js) {
    return url_for_asset($js, STATIC_JS_DIR);
}

//
// Tag Helpers

function stylesheet_link_tag($css, $options = array()) {
    $options['href'] = url_for_stylesheet($css);
    $options['rel'] = 'stylesheet';
    $options['type'] = 'text/css';
    return tag('link', '', $options);
}

function javascript_include_tag($js, $options = array()) {
    $options['src'] = url_for_javascript($js);
    $options['type'] = 'text/javascript';
    return tag('script', '', $options);
}

//
//

function h($html, $q = ENT_QUOTES) {
    return htmlentities($html, $q);
}

function tag($tag, $content, $attribs = array()) {
    $attribs = attribute_list($attribs);
    return "<{$tag}{$attribs}>{$content}</{$tag}>";
}

function empty_tag($tag, $attribs = array()) {
    $attribs = attribute_list($attribs);
    return "<{$tag}{$attribs}/>";
}

function attribute_list($attribs) {
    $out = '';
    foreach ($attribs as $k => $v) {
	    $v = h($v);
	    $out .= " $k='$v'";
    }
    return $out;
}

/**
 * Create an image tag.
 *
 * i('foo.png', array('alt' => 'Hello'))
 * i('bar.png', '#my-image')
 * i('baz.gif', '#my-image.my-class', array('width' => 500))
 */
function i($src, $options_or_selector = array(), $options = array()) {
    $options += parse_simple_selector($options_or_selector);
    $options['src'] = url_for_image($src);
    $options += array('alt' => '');
    return empty_tag('img', $as, $options);
}

function link_to($html, $url, $options = array()) {
    $options['href'] = generate_url($url);
    return tag('a', $html, $options);
}

function mail_to($html, $address = null, $options = array()) {
    if (is_array($address)) {
        $options = $address;
        $address = null;
    }
    if ($address === null) {
        $address = $html;
    }
    return link_to($html, "mailto:$address", $options);
}

//
// Input Helpers

function hidden_field_tag($name, $value, $options = array()) {
    return empty_tag('input', array(
        'type'  => 'hidden',
        'name'  => $name,
        'value' => $value
    ) + $options);
}

function hidden_field_tags($array, $prefix = '') {
    $html = '';
    foreach ($array as $k => $v) {
        $name = strlen($prefix) ? "{$prefix}[$k]" : $k;
        if (is_enumerable($v)) {
            $html .= hidden_field_tags($v, $name);
        } else {
            $html .= hidden_field_tag($name, $v);
        }
    }
    return $html;
}

function text_field_tag($name, $value = '', $options = array()) {
    return empty_tag('input', array(
        'type'  => 'text',
        'name'  => $name,
        'value' => $value
    ) + $options);
}

function password_field_tag($name, $value = '', $options = array()) {
    return empty_tag('input', array(
        'type'  => 'password',
        'name'  => $name,
        'value' => $value
    ) + $options);
}

function file_field_tag($name, $options = array()) {
    return empty_tag('input', array(
        'type'  => 'file',
        'name'  => $name
    ) + $options);
}

function check_box_tag($name, $checked = false, $options = array()) {
    $options['type'] = 'checkbox';
    $options['name'] = $name;
    $options['value'] = 1;
    if ($checked) $options['checked'] = 'checked';
    return hidden_field_tag($name, 0) . empty_tag('input', $options);
}

function radio_button_tag($name, $value, $current_value = null, $options = array()) {
    $options['type'] = 'radio';
    $options['name'] = $name;
    $options['value'] = $value;
    if ($value == $current_value || $current_value === true) $options['checked'] = 'checked';
    return empty_tag('input', $options);
}

function select_tag($name, $values, $selected = null, $options = array()) {
    $option_string = '';
    foreach ($values as $v) {
        $v = h($v);
        $s = ($selected !== null && $selected == $v) ? ' selected="selected"' : '';
        $option_string .= "<option{$sel}>{$v}</option>\n";
    }
    $options['name'] = $name;
    return tag('select', $option_string, $options);
}

function key_select_tag($name, $values, $selected = null, $options = array()) {
    $option_string = '';
    foreach ($values as $k => $v) {
        $s = ($selected !== null && $selected == $k) ? ' selected="selected"' : '';
        $k = h($k);
        $v = h($v);
        $option_string .= "<option value='$k'{$sel}>{$v}</option>\n";
    }
    $options['name'] = $name;
    return tag('select', $option_string, $options);
}

function text_area_tag($name, $value, $options = array()) {
    $options['name'] = $name;
    return tag('textarea', $value, $options + array('rows' => 6, 'cols' => 50));
}

?>
