<?php
class UrlTest extends TestCase
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
    
    function test_encode_whole_array() {
        $expect = 'a[b][c]=d&a[b][e]=f&a[g]=h&i=j';
        $expect = str_replace(array('[', ']'), array('%5B', '%5D'), $expect);
        
        assert_equal(
            $expect,
            array_url_encode($this->array)
        );
    }
    
    function test_encode_array_with_omit() {
        $expect = 'a[b][c]=d&a[b][e]=f&a[g]=h';
        $expect = str_replace(array('[', ']'), array('%5B', '%5D'), $expect);
        
        assert_equal(
            $expect,
            array_url_encode($this->array, 'i')
        );
    }
}
?>