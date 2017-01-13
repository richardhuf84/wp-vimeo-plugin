<?php

// Description: Configure our ACF (free) plugin

// Customize ACF path
add_filter('acf/settings/path', 'my_acf_settings_path');

function my_acf_settings_path( $path ) {

    // update path
    $path = plugin_dir_path( CUSTOM_PLUGIN_PATH ) . 'custom-plugin/acf/';

    // return
    return $path;

}


// Customize ACF dir
add_filter('acf/settings/dir', 'my_acf_settings_dir');

function my_acf_settings_dir( $dir ) {

    // update path
    $dir = plugin_dir_path( CUSTOM_PLUGIN_PATH ) . 'custom-plugin/acf/';

    // return
    return $dir;

}


// Hide ACF field group menu item
add_filter('acf/settings/show_admin', '__return_false');


// Include ACF
include_once( plugin_dir_path( CUSTOM_PLUGIN_PATH ) . 'custom-plugin/acf/acf.php' );

// Set ACF to lite, so it won't show in admin
// define( 'ACF_LITE', true );


?>
