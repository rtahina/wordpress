<?php
namespace RTRealEstate\Helpers;

use WP_Error;

class TaxonomyHelper {

    /**
     * Get taxonomy list
     * 
     * @param string $taxonomy
     * @return array|false
     */
    public static function get_taxonomy_list( string $taxonomy ): array|false {
        
        $terms_arr = [];

        $terms = get_terms( $taxonomy, array(
            'hide_empty' => false,
        ) );

        if ( is_wp_error ( $terms ) ) return false;

        return $terms;
    }

    /**
     * Insert property custom taxonomy
     * 
     * @param int $post_id
     * @param array $term_ids
     * @param string $taxonomy
     * @return bool
     */
    public static function insert_post_taxonomy( int $post_id, array $term_ids, $taxonomy ): bool {
        $term_ids = (array) $term_ids;
        if ( is_wp_error( wp_set_object_terms($post_id, $term_ids, $taxonomy) ) ) return false;
        else return true;
    }
}