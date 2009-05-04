<?php
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

?>