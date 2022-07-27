<?php
namespace RTRealEstate\Post_Types;

/**
 * Real Estate post type.
 */
class Property {

	/**
	 * Custom post type's slug.
	 *
	 * @var string
	 */
	private static $slug = 'property';

	/**
	 * Custom post type's slug, pluralized.
	 *
	 * @var string
	 */
	private static $slug_plural = 'properties';

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
		add_action( 'init', array( self::class, 'init' ), 10 );
		add_action( 'admin_init', array( self::class, 'add_capabilities' ), 11 );
		add_action( 'init', array( self::class, 'register_taxonomies' ), 10 );
		add_filter( 'post_updated_messages', array( self::class, 'updated_messages' ), 10 );
	}


	/**
	 * Registers the `properties` post type.
	 */
	public static function init() {

		register_post_type(
			self::$slug,
			array(
				'labels'            => array(
					'name'                  => __( 'Real Estate', 'real-estate-test' ),
					'singular_name'         => __( 'Real Estate', 'real-estate-test' ),
					'all_items'             => __( 'All Real Estates', 'real-estate-test' ),
					'archives'              => __( 'Real Estate Archives', 'real-estate-test' ),
					'attributes'            => __( 'Real Estate Attributes', 'real-estate-test' ),
					'insert_into_item'      => __( 'Insert into Real Estate', 'real-estate-test' ),
					'uploaded_to_this_item' => __( 'Uploaded to this Real Estate', 'real-estate-test' ),
					'featured_image'        => _x( 'Featured Image', self::$slug, 'real-estate-test' ),
					'set_featured_image'    => _x( 'Set featured image', self::$slug, 'real-estate-test' ),
					'remove_featured_image' => _x( 'Remove featured image', self::$slug, 'real-estate-test' ),
					'use_featured_image'    => _x( 'Use as featured image', self::$slug, 'real-estate-test' ),
					'filter_items_list'     => __( 'Filter Real Estates list', 'real-estate-test' ),
					'items_list_navigation' => __( 'Real Estates list navigation', 'real-estate-test' ),
					'items_list'            => __( 'Real Estates list', 'real-estate-test' ),
					'new_item'              => __( 'New Real Estate', 'real-estate-test' ),
					'add_new'               => __( 'Add New', 'real-estate-test' ),
					'add_new_item'          => __( 'Add New Real Estate', 'real-estate-test' ),
					'edit_item'             => __( 'Edit Real Estate', 'real-estate-test' ),
					'view_item'             => __( 'View Real Estate', 'real-estate-test' ),
					'view_items'            => __( 'View Real Estates', 'real-estate-test' ),
					'search_items'          => __( 'Search Real Estates', 'real-estate-test' ),
					'not_found'             => __( 'No Real Estates found', 'real-estate-test' ),
					'not_found_in_trash'    => __( 'No Real Estates found in trash', 'real-estate-test' ),
					'parent_item_colon'     => __( 'Parent Real Estate:', 'real-estate-test' ),
					'menu_name'             => __( 'Real Estates', 'real-estate-test' ),
				),
				'public'            => true,
				'hierarchical'      => false,
				'show_ui'           => true,
				'show_in_nav_menus' => true,
				'supports'          => array( 'title', 'editor', 'revisions', 'thumbnail' ),
				'has_archive'       => true,
				'rewrite'           => array( 'slug' => 'property' ),
				'query_var'         => true,
				'menu_position'     => 51,
				'show_in_rest'      => false,
				'capability_type'   => array( self::$slug, self::$slug_plural )
			)
		);

	}


	/**
	 * Sets the post updated messages.
	 *
	 * @param  array $messages Post updated messages.
	 * @return array Messages for the post type.
	 */
	public static function updated_messages( $messages ) {
		global $post;

		$permalink = get_permalink( $post );

		$messages[ self::$slug ] = array(
			0  => '', // Unused. Messages start at index 1.
			/* translators: %s: post permalink */
			1  => sprintf( __( 'Real Estate updated. <a target="_blank" href="%s">View Real Estate</a>', 'badgefactor2' ), esc_url( $permalink ) ),
			2  => __( 'Custom field updated.', 'badgefactor2' ),
			3  => __( 'Custom field deleted.', 'badgefactor2' ),
			4  => __( 'Real Estate updated.', 'badgefactor2' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Real Estate restored to revision from %s', 'badgefactor2' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			/* translators: %s: post permalink */
			6  => sprintf( __( 'Real Estate published. <a href="%s">View Real Estate</a>', 'badgefactor2' ), esc_url( $permalink ) ),
			7  => __( 'Real Estate saved.', 'badgefactor2' ),
			/* translators: %s: post permalink */
			8  => sprintf( __( 'Real Estate submitted. <a target="_blank" href="%s">Preview Real Estate</a>', 'badgefactor2' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
			9  => sprintf(
				/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
				__( 'Real Estate scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Real Estate</a>', 'badgefactor2' ),
				date_i18n( __( 'M j, Y @ G:i', 'badgefactor2' ), strtotime( $post->post_date ) ),
				esc_url( $permalink )
			),
			/* translators: %s: post permalink */
			10 => sprintf( __( 'Real Estate draft updated. <a target="_blank" href="%s">Preview Real Estate</a>', 'badgefactor2' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		);

		return $messages;
	}


	/**
	 * Add roles (capabilities) to custom post type.
	 *
	 * @return void
	 */
	public static function add_capabilities() {
		
		$capabilities = array(
			'read_' . self::$slug						=> array(
				'administrator',
			),
			'edit_' . self::$slug						=> array(
				'administrator',
			),
			'delete_' . self::$slug						=> array(
				'administrator',
			),
			'publish_' . self::$slug_plural				=> array(
				'administrator',
			),
			
			'edit_' . self::$slug_plural				=> array(
				'administrator',
			),
			'edit_others_' . self::$slug_plural			=> array(
				'administrator',
			),
			'edit_published_' . self::$slug_plural		=> array(
				'administrator',
			),
			'edit_private_' . self::$slug_plural		=> array(
				'administrator',
			),
			
			'read_private_' . self::$slug_plural		=> array(
				'administrator',
			),
			
			'delete_' . self::$slug_plural				=> array(
				'administrator',
			),
			'delete_others_' . self::$slug_plural		=> array(
				'administrator',
			),
			'delete_published_' . self::$slug_plural	=> array(
				'administrator',
			),
			'delete_private_' . self::$slug_plural		=> array(
				'administrator',
			),
			
			
		);

		foreach ( $capabilities as $capability => $roles ) {
			foreach ( $roles as $role ) {
				$role = get_role( $role );
				$role->add_cap( $capability, true );
			}
		}
		
	}

	/**
	 * Register taxonomies.
	 *
	 * @return void
	 */
	public static function register_taxonomies() {
		
        register_taxonomy(
			'property-type',
			array( self::$slug ),
			array(
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => __( 'Type', 'real-estate-test' ),
					'singular_name'     => __( 'Type', 'real-estate-test' ),
					'search_items'      => __( 'Search types', 'real-estate-test' ),
					'all_items'         => __( 'All types', 'real-estate-test' ),
					'parent_item'       => __( 'parent type', 'real-estate-test' ),
					'parent_item_colon' => __( 'parent type:', 'real-estate-test' ),
					'edit_item'         => __( 'Edit Type', 'real-estate-test' ),
					'update_item'       => __( 'Update Type', 'real-estate-test' ),
					'add_new_item'      => __( 'Add new Type', 'real-estate-test' ),
					'new_item_name'     => __( 'new Type Name', 'real-estate-test' ),
					'menu_name'         => __( 'Types', 'real-estate-test' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'property-type' ),
			)
		);
	}
}

$property = new Property();
