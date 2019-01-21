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

//==============================================================
// If this file is called directly, abort.
//==============================================================
if ( ! defined( 'WPINC' ) ) {
     die;
}

if(!class_exists('WP_List_Table')){
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

// ==============================================
//  Get Plugin Version
// ==============================================

function titan_moviepro_plugin_version() {
  if ( ! function_exists( 'get_plugins' ) )
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
  $plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
  $plugin_file = basename( ( __FILE__ ) );
  return $plugin_folder[$plugin_file]['Version'];
}

function titan_moviepro_enqueue_resources() {
  wp_enqueue_style( 'moviepro_style', plugins_url( '/css/moviepro.css' , __FILE__ ), array(), titan_moviepro_plugin_version(), 'all');
  wp_enqueue_style( 'moviepro_carousel_style', plugins_url( '/css/slick/slick.css' , __FILE__ ), array(), '1.9', 'all');
  wp_enqueue_style( 'fontAwesome', plugins_url( '/css/fontAwesome/css/all.min.css' , __FILE__ ), array(), '5.6.3', 'all');

  wp_enqueue_script( 'moviepro_carousel_script', plugin_dir_url( __FILE__ ) . 'js/slick/slick.min.js', array('jquery'), '1.9', true );
  wp_enqueue_script( 'moviepro_main_script', plugin_dir_url( __FILE__ ) . 'js/moviepro.js', array('jquery'), titan_moviepro_plugin_version(), true );
}
add_action('wp_enqueue_scripts', 'titan_moviepro_enqueue_resources');

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

//==============================================================
// Takes the result from the API and creates an HTML unordered
// list
//==============================================================
function create_movie_list($movies){
  $data = json_decode($movies);
  $baseURL = 'https://image.tmdb.org/t/p/';
  $imgSize = 'w300';
  $list = '<div class="moviepro-list">';
  foreach ($data->results as $movie) {
    $list .= '<div' . ' id="'. $movie->id . '"' .' class="moviepro-listItem">'
            . '<img src="' . $baseURL . $imgSize . $movie->{"poster_path"} . '" ' . 'alt="' . $movie->title . '"' . '/>'
            . '</div>';
  }
  $list .= '</div>';
  return $list;
}

// "https://api.themoviedb.org/3/genre/movie/list?language=en-US&api_key=50c1cc8af5a5e07e52ed728d348a4919"
//==============================================================
// Shortcode management
// Use [moviepro]
//==============================================================
function titan_moviepro_shortcode( $atts ) {
   $a = shortcode_atts( array(
      'genre' => '',
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
   . '&with_genres='     . urlencode(implode('%2C', array( $a['genre'])));

   $result = create_movie_list(http_GET_call($url));

   return '<div id="movie-container" class="moviepro"> ' . $result . '</div>';
}

add_shortcode( 'moviepro', 'titan_moviepro_shortcode' );