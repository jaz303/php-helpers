<?php
class TestCase
{
    function setup() {}
    function teardown() {}
    
    function run() {
        foreach (get_class_methods($this) as $m) {
            if (preg_match('/^test_/', $m)) {
                try {
                    $this->setup();
                    $this->$m();
                    $this->teardown();
                    echo ".";
                } catch (AssertionFailedException $afe) {
                    echo "F";
                } catch (Exception $e) {
                    echo "E";
                }
            }
        }
    }
}

class AssertionFailedException extends Exception {}

assert_options(ASSERT_ACTIVE,   1);
assert_options(ASSERT_WARNING,  0);
assert_options(ASSERT_CALLBACK, 'assert_callback');

function assert_callback() {
    throw new AssertionFailedException;
}

function ensure($val) {
    assert((bool) $val);
}

function assert_equal($left, $right) {
    assert($left == $right);
}

require '../helpers-5.3.php';
require 'array_path_test.php';
require 'query_string_test.php';

foreach (get_declared_classes() as $class) {
    if (is_subclass_of($class, 'TestCase')) {
        $test = new $class;
        $test->run();
    }
}

echo "\n";
?>