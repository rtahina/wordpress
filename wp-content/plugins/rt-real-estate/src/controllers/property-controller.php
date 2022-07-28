<?php
namespace RTRealEstate\Controllers;

use RTRealEstate\Helpers\PropertyHelper;
use RTRealEstate\Helpers\TaxonomyHelper;
use RTRealEstate\Helpers\MediaHelper;

/**
 * Course Controller Class.
 */
class PropertyController {

	/**
	 * Post Type.
	 *
	 * @var string
	 */
	protected $post_type;

	/**
	 * Data.
	 *
	 * @var array
	 */
	protected $property_data;

	public function __construct()
    {
        $this->post_type = 'property';
    }

	/**
	 * Returns post type paginated array.
	 * 
	 * @return array
	 */
	public function archive( $order_by = 'ID', $order = 'asc', $per_page = 10, $page = 0 ): array {
		$properties =  get_posts( array(
            'post_type' => $this->post_type,
            'numberposts' => $per_page,
            'paged' => $page,
            'orderby' => $order_by,
            'order' => $order
        ) );

		$property_data['properties'] = array();

		foreach( $properties as $property ) {
			
			$data = array();
			$data['ID'] = $property->ID;
			$data['permalink'] = get_the_permalink( $property->ID );
			$data['name'] = $property->post_title;
			$data['description'] = $property->post_content;
			$data['cost'] = get_field( 'property_cost', $property->ID );
			$data['address'] = get_field( 'property_address', $property->ID );
			$data['floor'] = get_field( 'floor', $property->ID );
			$data['property_gallery'] = get_field( 'property_gallery', $property->ID );
			$city = PropertyHelper::get_property_city( $property->ID );
			$data['city'] = ( $city ) ? $city : ""; 
			$data['types'] = PropertyHelper::get_property_type( $property->ID ); 
			
			if ( has_post_thumbnail( $property->ID ) ) {
				$image = get_the_post_thumbnail( $property->ID );
			} else {
				$image = '';
			}
			$data['image'] = $image;

			$this->property_data['properties'][] = $data;
		}

		return $this->property_data;
	}

	/**
	 * Returns array of property data.
	 * 
	 * @param int $id
	 * @return array|false
	 */
	public function single( int $id = 0 ): array {
		
		global $post;

		$id = ( $id > 0 ) ? $id : $post->ID; 
		
		$property =  get_post( $id );

		if ( is_null( $property ) ) return false;
	
		$data = array();
		$data['ID'] = $property->ID;
		$data['permalink'] = get_the_permalink( $property->ID );
		$data['name'] = $property->post_title;
		$data['description'] = $property->post_content;
		$data['cost'] = get_field( 'property_cost', $property->ID );
		$data['address'] = get_field( 'property_address', $property->ID );
		$data['floor'] = get_field( 'floor', $property->ID );
		$data['property_gallery'] = get_field( 'property_gallery', $property->ID );
		$city = PropertyHelper::get_property_city( $property->ID );
		$data['city'] = ( $city ) ? $city : ""; 
		$data['types'] = PropertyHelper::get_property_type( $property->ID ); 
		
		if ( has_post_thumbnail( $property->ID ) ) {
			$image = get_the_post_thumbnail( $property->ID );
		} else {
			$image = '';
		}
		
		$data['image'] = $image;

		return $data;
	}

	/**
	 * Creates a property object
	 * 
	 * @param array $data
	 * @return int|WP_Error
	 */
	public function create( $data ): int|\WP_Error {

		$args = array(
			'post_type' => $this->post_type,
			'post_author' => $data['user_id'],
			'post_title' => $data['name'],
			'post_content' => $data['description']
		);

		$property_id = wp_insert_post( $args );

		if ( is_wp_error( $property_id ) ) return $property_id;

		// Property image
		$file = MediaHelper::upload_files( $data['image'] );		
        $attachment_id = MediaHelper::insert_attachment( $property_id, $file );
		set_post_thumbnail( $property_id, $attachment_id );

		// ACF fields
		update_field( 'property_cost', $data['cost'], $property_id );
		update_field( 'property_address', $data['address'], $property_id );
		update_field( 'floor', $data['floor'], $property_id );
		
		// inserts types
		$type_ids = array_map( function( $type ) {
			return ( int ) $type;
		}, ( array ) $data['types'] );
		TaxonomyHelper::insert_post_taxonomy( $property_id, $type_ids, 'property-type' );
		
		// inserts city
		add_post_meta( $property_id, '_property_city_id', $data['city'], true, );

		return $property_id;
	}

	/**
	 * Ajax add property
	 */
	public static function ajax_add_property() {
		$return = array(
			'success'  => true,
		);
		
		if ( ! wp_verify_nonce( $_REQUEST['add-property-nonce'], 'add-property' ) ) {
			header( 'Content-Type: application/json' );
			echo json_encode( array( 'success' => false ) );
			exit;
		}

		$user_id = get_current_user_id();

		if ( $user_id == 0 ) {
			header( 'Content-Type: application/json' );
			echo json_encode( array( 
				'success' => false,
				'message' => 'User not logged in',
				) );
			exit;
		}
		
		$data['user_id']            = $user_id;
		$data['name']            = $_REQUEST['propertyName'] ?? '';
		$data['description']             = $_REQUEST['propertyDescription'] ?? '';
		$data['cost']                 = $_REQUEST['propertyCost'] ?? '';
		$data['address']              = $_REQUEST['propertyAddress'] ?? '';
		$data['floor'] = $_REQUEST['propertyFloor'] ?? '';
		$data['city'] = $_REQUEST['propertyCity'] ?? 0;
		$data['image'] = $_FILES['propertyImage'] ?? null;
		
		$type_ids = explode( ',', $_REQUEST['propertyTypes'] );
		$data['types'] = $type_ids;

		if ( trim( $data['name'] ) == '' ) {
			header( 'Content-Type: application/json' );
			echo json_encode( array( 
				'success' => false,
				'message' => 'Property must have a name',
				) );
			exit;
		}

		$property = (new self)->create( $data );

		if ( is_wp_error( $property ) ) {
			header( 'Content-Type: application/json' );
			echo json_encode( array( 
				'success' => false,
				'message' => 'Property insertion failed',
				) );
			exit;
		}

		header( 'Content-Type: application/json' );
		echo json_encode( $return );
		die;
	}
}