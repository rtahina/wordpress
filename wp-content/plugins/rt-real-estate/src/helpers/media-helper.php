<?php
namespace RTRealEstate\Helpers;

class MediaHelper {
    /**
     * Uploads an image in the wp uploads dir
     * 
     * @return array | Wp_Error 
     */
    public static function upload_files( $image_file )
    {
        $uploaded_files = array();

        if ( isset( $image_file['name'] ) ) {
            if ( !is_array( $image_file['name'] ) ) {
                
                $validation = self::validate_file_upload( $image_file );
                
                if ( is_wp_error( $validation ) ) {
                    return $validation;
                }    
                
                $uploaded_files[] = wp_upload_bits(
                    $image_file['name'], 
                    null, 
                    file_get_contents( $image_file['tmp_name'] )
                );

            } else {
                
                for( $i = 0; $i < count( $image_file['name'] ); $i++ ) {
                    
                    $validation = self::validate_file_upload( $image_file );
                
                    if ( is_wp_error( $validation ) ) {
                        return $validation;
                    }

                    $uploaded_files[] = wp_upload_bits(
                        $image_file['name'][$i], 
                        null, 
                        file_get_contents( $image_file['tmp_name'][$i] )
                    );
                }
            }
        }

        return $uploaded_files;
    }

    /**
     * Insert an image as a wp attachment
     * 
     * @param array $image_data      
     * @param int $post_id
     * @return mixed array $attachment_ids | int $attachment_id
     */
    public static function insert_attachment( $post_id, $image_data ) 
    {
        $image_data     = (array) $image_data;
        $attachment_ids = array();

        for ( $i = 0; $i < count( $image_data ); $i++ ) {
            
            $filename = $image_data[$i]['file'];

            // Get the path to the upload directory.
            $wp_upload_dir = wp_upload_dir();        
            
            // Prepare an array of post data for the attachment.
            $attachment = array(
                'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
                'post_mime_type' => $image_data[$i]['type'],
                'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );
            
            // Insert the attachment.
            $attachment_id = wp_insert_attachment( $attachment, $filename, $post_id );
            $attachment_ids[] = $attachment_id;
            
            // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            
            // Generate the metadata for the attachment, and update the database record.
            $attach_data = wp_generate_attachment_metadata( $attachment_id, $filename );
            wp_update_attachment_metadata( $attachment_id, $attach_data );
        }

        if ( count( $attachment_ids ) == 1 ) {
            return $attachment_ids[0];
        }

        return $attachment_ids;
    }

    /**
     * Update an attachment image file
     * 
     * @param array $image_data      
     * @param int $post_id
     * @return mixed array $attachment_ids | int $attachment_id
     */
    public static function update_attachment( $attachment_id, $image_data ) 
    {
        $image_data     = (array) $image_data;
        $filename = $image_data[0]['file']; // always handles single image file

        // Get the path to the upload directory.
        $wp_upload_dir = wp_upload_dir();
        
        update_attached_file(
            $attachment_id,
            $wp_upload_dir['url'] . '/' . basename( $filename )
        );

         // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
         require_once( ABSPATH . 'wp-admin/includes/image.php' );

        // Generate the metadata for the attachment, and update the database record.
        $attach_data = wp_generate_attachment_metadata( $attachment_id, $filename );
        wp_update_attachment_metadata( $attachment_id, $attach_data );

        return $attachment_id;
    }

    /**
     * Validate files that are about to be uplaoded
     */
    public static function validate_file_upload( $image_file )
    {
        $max_file_size  = 6000000; // oct
        $allowed_file_types  = array( 'image/jpeg', 'image/png' );

        if ( !is_array( $image_file['name'] ) ) {
            
            // We don't need this, images are optimized by EWWWW plugin
            if ( $image_file['size'] > $max_file_size ) {
                return new \WP_Error( 'file_upload_error', __( "You cannot upload a file more than 6Mo" ) );   
            }
            if ( !in_array( $image_file['type'], $allowed_file_types ) ) {
                return new \WP_Error( 'file_upload_error', __( "This file type is not allowed" ) );
            }
        } else {
            for( $i = 0; $i < count( $image_file['name'] ); $i++ ) {
                    
                if ( $image_file['size'][$i] > $max_file_size ) {
                    return new \WP_Error( 'file_upload_error', __( "File size must be under " . ( $max_file_size / 1000 ) . " Ko" ) );   
                }
                if ( !in_array( $image_file['type'][$i], $allowed_file_types ) ) {
                    return new \WP_Error( 'file_upload_error', __( "This file type is not allowed" ) );
                }
            }
        }

        return true;
    }
}