<?php
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