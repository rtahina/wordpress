<?php
namespace RTRealEstate;

class AdvancedCustomField {
    
    public function __construct()
    {
        self::init_hooks();
    }


	/**
	 * Init hooks.
	 *
	 * @return void
	 */
	public static function init_hooks() {
		add_action( 'acf/init', array( self::class, 'my_acf_add_local_field_groups' ) );
	}

    /**
     * ADF group field
     */
    public static function my_acf_add_local_field_groups() {
        
        if ( class_exists('RTRealEstate\PostTypes\Property') ) {
            if( function_exists('acf_add_local_field_group') ) {
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
                            'key' => 'floor',
                            'label' => 'Floor',
                            'name' => 'floor',
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
        }
    }
}