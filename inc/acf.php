<?php
function my_acf_prepare_field($field) {
    $enabled_fields = array('menu_button_icon', 'card_icon', 'icon_select');

    // var_dump($field['_name']);

    if ((!in_array($field['_name'], $enabled_fields) && !strpos($field['_name'], 'icon__select'))) {
        return $field;
    }
    // Lock-in the value "Example".
    $choices = array(
        false => 'No icon',
    );
    $icons = get_all_icons_names();
    foreach ($icons as $icon) {
        $choices[$icon] = $icon;
    }
    // map keys
    $field['choices'] = $choices;
    return $field;
}

// Apply to all fields.
// add_filter('acf/prepare_field', 'my_acf_prepare_field');

// Apply to select fields.
// add_filter('acf/prepare_field/type=select', 'my_acf_prepare_field');

add_filter('acf/prepare_field/type=select', 'my_acf_prepare_field');
