<?php
/**
 * Real Estate
 *
 * @package RTRealEstate
 *
 * Plugin Name: Real Estate
 * Author: Tahina
 * Version: 1.0.0
 * Author URI: 
 * License: GNU AGPL
 * Text Domain: rt-real-estate
 * Domain Path: /languages
 *
 */


defined( 'ABSPATH' ) || exit;

load_plugin_textdomain( 'rt-real-estate', false, basename( dirname( __FILE__ ) ) . '/languages' );

// includes required files
require_once('src/post-types/property.php');
require_once('src/post-types/city.php');
require_once('src/controllers/property-controller.php');
require_once('src/controllers/city-controller.php');
require_once('src/acf.php');
require_once('src/meta-boxes.php');
require_once('src/helpers/property-helper.php');
require_once('src/helpers/taxonomy-helper.php');
require_once('src/helpers/media-helper.php');

function run() {
	new \RTRealEstate\PostTypes\Property();
    new \RTRealEstate\PostTypes\City();
    new \RTRealEstate\AdvancedCustomField();

    init_hooks();
}

function init_hooks() {
    
    if ( is_admin() ) {
        add_action( 'load-post.php',     'add_city_meta_box' );
        add_action( 'load-post-new.php', 'add_city_meta_box' );
    }

    add_action( 'wp_ajax_add_property', [\RTRealEstate\Controllers\PropertyController::class, 'ajax_add_property'] );
    add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );
}

/**
 * City metabox for property post type
 */
function add_city_meta_box() {
    new \RTRealEstate\MetaBoxes();
}

/**
 * Real estate scripts
 */

function add_theme_scripts() {
	wp_enqueue_script( 'rt-real-estate-js', plugin_dir_url( __FILE__ ) . 'assets/js/rt-real-estate.js', array('jquery'), '1.0.0', true);
	wp_localize_script( 'rt-real-estate-js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

/**
 * Run
 */
run();