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

function text_area_tag($name, $value, $options = array()) {
    $options['name'] = $name;
    return tag('textarea', $value, $options + array('rows' => 6, 'cols' => 50));
}

function select_box($name, $choices, $selected = null, $options = array()) {
    
    $options += array('keys' => true, 'multiple' => false, 'groups' => false);
    $options['name'] = $name;
    
    $ofs_opts = array('groups' => $options['groups'], 'keys' => $options['keys']);
    unset($options['groups']);
    unset($options['keys']);
    
    if ($options['multiple']) $selected = (array) $selected;
    
    return tag('select', options_for_select($choices, $selected, $ofs_opts), $options);
    
}

function options_for_select($choices, $selected = null, $options = array()) {
    $options += array('groups' => false, 'keys' => true);
    $html = '';
    if ($options['groups']) {
        foreach ($choices as $group_label => $group_options) {
            $html .= '<optgroup label="' . h($group_label) . '">';
            $html .= option_group($group_options, $selected, $options['keys']);
            $html .= '</optgroup>';
        }
    } else {
        $html .= option_group($choices, $selected, $options['keys']);
    }
    return $html;
}

function option_group($choices, $selected, $use_keys) {
    $html = '';
    foreach ($choices as $k => $v) {
        $c = $use_keys ? $k : $v;
        $s = is_array($selected) ? in_array($c, $selected) : ($selected == $c);
        $s = $s ? ' selected="selected"' : '';
        $v = htmlentities($v);
        if ($use_keys) {
            $k = htmlentities($k);
            $html .= "<option value=\"$k\"{$s}>{$v}</option>";
        } else {
            $html .= "<option{$s}>{$v}</option>";
        }
    }
    return $html;
}

?>