<?php
/**
* @since             1.0.0
* @package           titan-moviepro-plugin
* 
* @wordpress-plugin
* Plugin Name: Movie Pro
* Plugin URI: https://github.com/titandevstudio/wordpress_movieplugin
* Description: A plugin to create sliders and carousels using movie information gathered via HTTP calls to The Movie DB's API.
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

//==============================================================
// Makes an http GET call using the specified URL and returns
// the result.
//==============================================================
function http_GET_call($url) {
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "{}",
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    return " { 'cURL Error #':" . $err . " } ";
  } else {
    return $response;
  }
}

// "https://api.themoviedb.org/3/genre/movie/list?language=en-US&api_key=50c1cc8af5a5e07e52ed728d348a4919"
//==============================================================
// Shortcode management
// Use [moviepro]
//==============================================================

function titan_moviepro_shortcode( $atts ) {
   $a = shortcode_atts( array(
      'genre' => array(),
      'language' => 'en-US',
      'sort_by' => 'popularity.desc',
      'include_adult' => 'false',
      'include_video' => 'false',
      'page' => 1
   ), $atts );

   $url = "https://api.themoviedb.org/3/discover/movie?api_key=50c1cc8af5a5e07e52ed728d348a4919"
   . '&language='       . $a['language']
   . '&sort_by='        . $a['sort_by']
   . '&include_adult='  . $a['include_adult']
   . '&include_video='  . $a['include_video']
   . '&page='           . $a['page']
   . '$with_genre='     . implode('%2C', $a['genre']);

   $result = http_GET_call($url);

   return '<div id="movie-container" class="moviepro"> ' . $result . '</div>';
}

add_shortcode( 'moviepro', 'titan_moviepro_shortcode' );