<?php
if ( class_exists('RTRealEstate\Post_Types\Property') ) {
    if( function_exists('acf_add_local_field_group') ) {
        function my_acf_add_local_field_groups() {
            acf_add_local_field_group(array (
                'key' => 'acf_extra_fields',
                'title' => 'Extra Fields',
                'fields' => array (
                    array (
                        'key' => 'property_cost',
                        'label' => 'Cost',
                        'name' => 'property_cost',
                        'type' => 'text',
                        ),
                    array (
                        'key' => 'property_address',
                        'label' => 'Address',
                        'name' => 'property_address',
                        'type' => 'text',
                        ),    
                    array (
                        'key' => 'living_area',
                        'label' => 'Living area',
                        'name' => 'living_area',
                        'type' => 'text',
                    ),
                    array (
                        'key' => 'property_gallery',
                        'label' => 'Gallery',
                        'name' => 'property_gallery',
                        'type' => 'gallery',
                        'min' => '0',
                        'max' => '10',
                        'insert' => 'append',
                        'library' => 'all',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                    )
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'property',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
            ));
        }
        
        add_action('acf/init', 'my_acf_add_local_field_groups');
        
    }
}