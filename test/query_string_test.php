<?php
class QueryStringTest extends ztest\UnitTestCase
{
    function setup() {
        $this->array = array(
            'a' => array(
                'b' => array(
                    'c' => 'd',
                    'e' => 'f'
                ),
                'g' => 'h'
            ),
            'i' => 'j'
        );
    }
    
    function test_query_string_generates_simple_query_string() {
        assert_equal('a=1&b=2', query_string(array('a' => 1, 'b' => 2)));
    }
    
    function test_query_string_replaces_paths() {
        assert_equal('a=1&b=10', query_string(array('a' => 1, 'b' => 2), array('b' => 10)));
    }
    
    function test_query_string_removes_paths() {
        assert_equal('a=1', query_string(array('a' => 1, 'b' => 2), null, 'b'));
    }
    
    function test_query_string_fragment_with_one_string_param() {
        assert_equal('foo=bar', query_string_fragment('foo=bar'));
    }
    
    function test_query_string_fragment_with_two_string_params() {
        assert_equal('foo=bar', query_string_fragment('foo', 'bar'));
    }
    
    function test_query_string_fragment_with_one_array_param() {
        assert_equal('foo=bar', query_string_fragment(array('foo' => 'bar')));
    }
    
    function test_url_append_inserts_question_mark_if_no_query_string() {
        assert_equal('/moose?foo=bar', url_append('/moose', 'foo=bar'));
    }
    
    function test_url_append_inserts_ampersand_if_query_string() {
        assert_equal('/moose?b=b&foo=bar', url_append('/moose?b=b', 'foo=bar'));
    }
    
    function test_query_string_append_inserts_no_prefix_if_empty() {
        assert_equal('foo=bar', query_string_append('', 'foo=bar'));
    }
    
    function test_query_string_append_inserts_ampersand_if_non_empty() {
        assert_equal('b=b&foo=bar', query_string_append('b=b', 'foo=bar'));
    }
    
    function test_array_url_encode() {
        $expect = 'a[b][c]=d&a[b][e]=f&a[g]=h&i=j';
        $expect = str_replace(array('[', ']'), array('%5B', '%5D'), $expect);
        
        assert_equal(
            $expect,
            array_url_encode($this->array)
        );
    }
    
    function test_array_url_encode_with_omit() {
        $expect = 'a[b][c]=d&a[b][e]=f&a[g]=h';
        $expect = str_replace(array('[', ']'), array('%5B', '%5D'), $expect);
        
        assert_equal(
            $expect,
            array_url_encode($this->array, 'i')
        );
    }
}
?>