<?php
class ArrayPathTest extends ztest\UnitTestCase
{
    function setup() {
        $this->foo = array(
            'bar' => 5,
            'baz' => array(
                'a' => 10,
                'b' => 20,
                'c' => array(
                    'd' => 'moose',
                    'e' => 'cow'
                )
            )
        );
    }
    
    function test_array_path() {
        assert_equal(5, array_path($this->foo, 'bar'));
        assert_equal('moose', array_path($this->foo, 'baz.c.d'));
        assert_equal('not-found', array_path($this->foo, 'what.the.boat', 'not-found'));
    }
    
    function test_array_path_unset() {
        array_path_unset($this->foo, 'baz.c');
        assert_equal(
            array('bar' => 5, 'baz' => array('a' => 10, 'b' => 20)),
            $this->foo
        );
    }
    
    function test_array_without_path() {
        $without = array_without_path($this->foo, 'baz.c');
        array_path_unset($this->foo, 'baz.c');
        assert_equal($without, $this->foo);
    }
    
    function test_array_path_replace_with_single_component() {
        array_path_replace($this->foo, 'baz', 100);
        assert_equal(array('bar' => 5, 'baz' => 100), $this->foo);
    }
    
    function test_array_path_replace_with_complex_component() {
        array_path_replace($this->foo, 'baz.c', 'zomg');
        assert_equal(array('bar' => 5, 'baz' => array('a' => 10, 'b' => 20, 'c' => 'zomg')), $this->foo);
    }
    
    function test_array_path_replace_with_non_existant_component() {
        array_path_replace($this->foo, 'bleem.blom.blam', 'HELLO THAR');
        assert_equal(array(
            'bar' => 5,
            'baz' => array(
                'a' => 10,
                'b' => 20,
                'c' => array(
                    'd' => 'moose',
                    'e' => 'cow'
                )
            ),
            'bleem' => array(
                'blom' => array(
                    'blam' => 'HELLO THAR'
                )
            )
        ), $this->foo);
    }
    
    function test_array_path_to_name() {
        
        $expect = array(
            'foo'           => 'foo',
            'foo.bar'       => 'foo[bar]',
            'foo.bar.baz'   => 'foo[bar][baz]'
        );
        
        foreach ($expect as $in => $out) {
            assert_equal($out, array_path_to_name($in));
        }
        
    }
}
?>