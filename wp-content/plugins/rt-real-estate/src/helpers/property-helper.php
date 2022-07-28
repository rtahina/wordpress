<?php
namespace RTRealEstate\Helpers;

use WP_Error;

class PropertyHelper {

    /**
     * Get the property's city
     * 
     * @param int $property_id
     * @return \WP_Post|false
     */
    public static function get_property_city( int $property_id ): \WP_Post|false {
        $city_id = get_post_meta( $property_id, '_property_city_id', true );
        if ( false === $city_id || '' == $city_id || 0 == $city_id ) return false;
        
        $city = get_post( $city_id );
        if ( is_null( $city ) ) return false;
        else return $city;
    }

    /**
     * Get the property's type
     * 
     * @param int $property_id
     * @return string
     */
    public static function get_property_type( int $property_id = 0, string $separator = ', ' ): string {
        
        global $post;

        $property_id = ( $property_id > 0 ) ? $property_id : $post->ID ;

        $property_types = [];
        $types = get_the_terms( $property_id, 'property-type' );

        if ( false === $types || is_wp_error ( $types ) ) return "";

        foreach( $types as $type ) {
            $property_types[] = $type->name;
        }

        return join( $separator, $property_types );
    }

    /**
     * Get the properties for a givien city
     * 
     * @param int $property_id
     * @return array
     */
    public static function get_property_list_of_single_city( int $property_id = 0 ): array {
        
        global $post, $wpdb;

        $property_id = ( $property_id > 0 ) ? $property_id : $post->ID ;

        $property_ids = [];

        
        $qry = $wpdb->prepare( 
            "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_property_city_id' AND meta_value = %d",
            $property_id
        );

        $res = $wpdb->get_results( $qry );

        if ( $res )
            $property_ids = array_map( function ( $property ) {
                return $property->post_id;
            }, $res );

        $properties = get_posts( array(
            'post_type' => 'property',
            'post__in' => $property_ids
        ) );
        
        $property_list = array();

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
			$city = self::get_property_city( $property->ID );
			$data['city'] = ( $city ) ? $city : ""; 
			$data['types'] = self::get_property_type( $property->ID ); 
			
			if ( has_post_thumbnail( $property->ID ) ) {
				$image = get_the_post_thumbnail( $property->ID );
			} else {
				$image = '';
			}
			$data['image'] = $image;

			$property_list[] = $data;
		}
        
        return $property_list;
    }
}