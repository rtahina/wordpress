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
}