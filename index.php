<?php 
/*
Plugin Name: ALT Lab Custom Pressbooks
Plugin URI:  https://github.com/
Description: For the press and the books
Version:     1.0
Author:      ALT Lab
Author URI:  http://altlab.vcu.edu
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: my-toolset

*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


add_action('wp_enqueue_scripts', 'prefix_load_scripts');

function previx_load_scripts() {                           
    $deps = array('jquery');
    $version= '1.0'; 
    $in_footer = true;    
    wp_enqueue_script('prefix-main-js', plugin_dir_url( __FILE__) . 'js/prefix-main.js', $deps, $version, $in_footer); 
    wp_enqueue_style( 'prefix-main-css', plugin_dir_url( __FILE__) . 'css/prefix-main.css');
}



/*-------------------------------------------NEW FILE TYPES ALLOWED HERE-------------------------------------------*/
//allow some additional file types for upload
function my_custom_mime_types( $mimes ) {

        // New allowed mime types.
        $mimes['svg'] = 'image/svg+xml';
        $mimes['svgz'] = 'image/svg+xml';
        $mimes['studio3'] = 'application/octet-stream';

        // Optional. Remove a mime type.
        unset( $mimes['exe'] );

    return $mimes;
}
add_filter( 'upload_mimes', 'my_custom_mime_types' );


/*-------------------------------------------Remove the h1 tag from the WordPress editor.-------------------------------------------*/
/**
 *  Remove the h1 tag from the WordPress editor.
 *
 *  @param   array  $settings  The array of editor settings
 *  @return  array             The modified edit settings
 */

function my_format_TinyMCE( $in ) {
        $in['block_formats'] = "Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6;Preformatted=pre";
    return $in;
}
add_filter( 'tiny_mce_before_init', 'my_format_TinyMCE' );

/*------------------------------------H5P  ---------------------------------------------------*/
// Make H5P embeds flexible
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( is_plugin_active(  'h5p/h5p.php' ) ) {
  //plugin is activated
     add_action('wp_enqueue_scripts', 'h5pflex_widget_enqueue_script');
}


function h5pflex_widget_enqueue_script() {
    $h5p_script = plugins_url( 'h5p/h5p-php-library/js/h5p-resizer.js', __DIR__);
    wp_enqueue_script( 'h5p_flex', $h5p_script, true );

    }

/*------------------------------------ NEXT PAGE  ---------------------------------------------------*/

/**
 * Replace [nextpage] with <!--nextpage--> through the 'the_posts' filter.
 *
 * @see http://wordpress.stackexchange.com/a/183980/26350
 */

! is_admin() && add_filter( 'the_posts', function( $posts )
{
    $posts = array_map( function( $p )
    {
        if ( false !== strpos( $p->post_content, '[nextpage]' ) )
            $p->post_content = str_replace( '[nextpage]', '<!--nextpage-->', $p->post_content ); 
        return $p;
    }, $posts );
    return $posts;
});