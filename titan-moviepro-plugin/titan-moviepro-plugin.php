<?php
/**
* @since             1.0.0
* @package           Salandash-user-editor
* 
* @wordpress-plugin
* Plugin Name: Movie Pro
* Plugin URI: https://github.com/titandevstudio/wordpress_movieplugin
* Description: A plugin to create sliders and carousels based on movie information gathered via API call to The Movie DB's API.
* Version: 0.1
* Author: Javier Castillo
* Author URI: 
* License: MIT
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
     die;
}
if(!class_exists('WP_List_Table')){
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
// Include the dependencies needed to instantiate the plugin.
foreach ( glob( plugin_dir_path( __FILE__ ) . 'admin/*.php' ) as $file ) {
    include_once $file;
}
 
add_action( 'plugins_loaded', 'titan_movie_pro' );
/**
 * Starts the plugin.
 *
 * @since 1.0.0
 */
function titan_movie_pro() {
  $serializer = new Serializer();
  $serializer->init();
  $plugin = new Submenu( new Submenu_Page( $serializer ) );
    $plugin->init();
}