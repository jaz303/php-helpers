<?php
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

?>