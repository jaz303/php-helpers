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

?>