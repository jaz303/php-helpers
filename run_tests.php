<?php
require dirname(__FILE__) . '/vendor/ztest/ztest.php';
require dirname(__FILE__) . '/helpers-5.3.php';

set_time_limit(0);

$suite = new ztest\TestSuite("php-helpers test suite");
$suite->require_all('test');
$suite->auto_fill();

$reporter = new ztest\ConsoleReporter;
$reporter->enable_color();

$suite->run($reporter);
?>