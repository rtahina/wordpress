<?php
namespace RTRealEstate;

/**
 * The Class.
 */
class MetaBoxes {
 
    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post',      array( $this, 'save'         ) );
    }
 
    /**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'property' );
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'some_meta_box_name',
                __( 'Choose a city', 'real-estate-test' ),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
        }
    }
 
    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {
 
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
 
        // Check if our nonce is set.
        if ( ! isset( $_POST['property_custom_box_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST['property_custom_box_nonce'];
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'property_custom_box' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        
        // Sanitize the user input.
        $mydata = sanitize_text_field( $_POST['property_city'] );
 
        // Update the meta field.
        update_post_meta( $post_id, '_property_city_id', $mydata );
    }
 
 
    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {
 
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'property_custom_box', 'property_custom_box_nonce' );
 
        // Use get_post_meta to retrieve an existing value from the database.
        $value = get_post_meta( $post->ID, '_property_city_id', true );
        
        $cities = \RTRealEstate\Post_Types\City::get_all_cites();

        // Display the form, using the current value.
        ?>
        <select id="property_city_meta_box" name="property_city">
            <option value="0">Choose a city</option>
            <?php foreach ( $cities as $city ) : ?>
                <option value="<?php echo $city->ID; ?>"<?php echo ( $city->ID == $value ) ? ' selected="selected"' : ''; ?>><?php echo $city->post_title; ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }
}