<?php
/*
Plugin Name: Custom Plugin
Plugin URI:  https://richardhuf.com.au/plugins/custom-plugin
Description: Custom Plugin - learning stuff.
Version:     1.0
Author:      Richard Huf
Author URI:  https://richardhuf.com.au
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: custom-plugin
Domain Path: /languages
*/

// 0. Disallow direct access
defined( 'ABSPATH' ) or die( 'No direct access.' );

define("CUSTOM_PLUGIN_PATH", plugin_dir_path( __FILE__ ));

// Get API key from plugin options
define("CUSTOM_PLUGIN_API_KEY", get_option('custom_plugin_options')['custom_plugin_field_api']);

// API - base url
define("CUSTOM_PLUGIN_BASE_URL", 'https://api.vimeo.com/');



// INCLUDE ADVANCED CUSTOM FIELDS PLUGIN IN THIS PLUGIN
////////////////////////////////////////////////////////////////////////////////

// include advanced custom fields plugin files
include_once(CUSTOM_PLUGIN_PATH . 'acf/acf.php');



// PLUGIN INCLUDES
////////////////////////////////////////////////////////////////////////////////

// Include file that sets some config so we can use ACF plugin within our plugin
include_once(CUSTOM_PLUGIN_PATH . 'inc/acf-config.php');
// Load Vimeo api file
include_once(CUSTOM_PLUGIN_PATH . 'inc/vimeo-api/vimeo-api-interface.php');



// ADMIN AREA INCLUDES
////////////////////////////////////////////////////////////////////////////////

// plugin settings page - settings fields
include_once(CUSTOM_PLUGIN_PATH . 'admin/custom-plugin-admin.php');
// Register fields for the Video CPT
include_once(CUSTOM_PLUGIN_PATH . 'admin/cpt-fields.php');
// Add meta box to CTP to lookup video
include_once(CUSTOM_PLUGIN_PATH . 'admin/class-add-meta-box.php');


// CREATE A NEW CUSTOM POST TYPE: VIMEO VIDEO
////////////////////////////////////////////////////////////////////////////////

// Setup post types
function custom_plugin_setup_post_types()
{

	$labels = array(
		'name'               => _x( 'Vimeo Video', 'Vimeo Video', 'custom-plugin' ),
		'singular_name'      => _x( 'Vimeo Video', 'post type singular name', 'custom-plugin' ),
		'menu_name'          => _x( 'Vimeo Videos', 'admin menu', 'custom-plugin' ),
		'name_admin_bar'     => _x( 'Vimeo Video', 'add new on admin bar', 'custom-plugin' ),
		'add_new'            => _x( 'Add New', 'vimeo-video', 'custom-plugin' ),
		'add_new_item'       => __( 'Add New Vimeo Video', 'custom-plugin' ),
		'new_item'           => __( 'New Vimeo Video', 'custom-plugin' ),
		'edit_item'          => __( 'Edit Vimeo Video', 'custom-plugin' ),
		'view_item'          => __( 'View Vimeo Video', 'custom-plugin' ),
		'all_items'          => __( 'All Vimeo Videos', 'custom-plugin' ),
		'search_items'       => __( 'Search Vimeo Videos', 'custom-plugin' ),
		'parent_item_colon'  => __( 'Parent Vimeo Videos:', 'custom-plugin' ),
		'not_found'          => __( 'No vimeo video found.', 'custom-plugin' ),
		'not_found_in_trash' => __( 'No vimeo video found in Trash.', 'custom-plugin' )
	);

	$args = array(
		'labels'             => $labels,
    'description'        => __( 'Description.', 'custom-plugin' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'vimeo-video' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title' )
	);

	register_post_type( 'vimeo-video', $args );
}

add_action( 'init', 'custom_plugin_setup_post_types' );


// PLUGIN ACTIVATION
////////////////////////////////////////////////////////////////////////////////

// On activation, we run through this install function
function custom_plugin_install()
{
    // trigger our function that registers the custom post type
    custom_plugin_setup_post_types();

    // clear the permalinks after the post type has been registered
    flush_rewrite_rules();
}

// Activation hook
register_activation_hook( __FILE__, 'custom_plugin_install' );


// PLUGIN DEACTIVATION
////////////////////////////////////////////////////////////////////////////////

// Deactivation function
function custom_plugin_deactivation()
{
    // our post type will be automatically removed, so no need to unregister it

    // clear the permalinks to remove our post type's rules
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook( __FILE__, 'custom_plugin_deactivation' );
