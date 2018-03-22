<?php
use mZoo\Instructors as AC;

/* This file contains main plugin class and, defines and plugin loader.
 *
 * Match instructors with clients based on specific criteris using Isotope.
 *
 * @package MZOOINSTRUCTOR
 *
 * Plugin Name: Instructors Post Type
 * Description: 	Manage and Display instructors based on specific criteris using Isotope
 * Full Description: Manage and Display instructors based on specific criteris using Isotope. Instructors is the name of a CPT, which includes custom fields and also accepts tags and categories.
 * Version: 		1.0.0
 * Author: 			Mike iLL Kilmer mZoo.org
 * Author URI: 		http://www.mZoo.com
 * Plugin URI: 		http://www.mZoo.com
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: 	mz_instructors
 * Domain Path: 	/languages
 * */

error_reporting(E_STRICT);
if ( !defined( 'WPINC' ) ) {
    die;
}

require __DIR__ . "/src/Autoload.php" ;
spl_autoload_register( [ new mZoo\Loader\Autoload( 'mZoo', __DIR__ . '/src/' ), 'load' ] );
//define plugin path and directory
define( 'MZOO_INSTRUCTOR_DIR', plugin_dir_path( __FILE__ ) );
define( 'MZOO_INSTRUCTOR_URL', plugin_dir_url( __FILE__ ) );

register_deactivation_hook( __FILE__, 'mzoo_instructor_deactivation' );

function mzoo_instructor_deactivation() {
	// TODO: Unregister taxonomies and stuff
}

// Define all our custom fields here:
$our_custom_fields = new AC\MZOO_Custom_Fields(array(
													'contact_info' => 'Contact Info',
													'certifications' => 'Certifications'
													));
add_action( 'init', array( 'mZoo\\Instructors\\MZOO_Instructors', 'register_post_type' ), 0 );
add_action( 'init', array( 'mZoo\\Instructors\\MZOO_Instructors', 'register_tags' ), 0 );
add_action( 'init', array( 'mZoo\\Instructors\\MZOO_Instructors', 'register_categories' ), 0 );
add_action( 'init', array( 'mZoo\\Instructors\\MZOO_Instructors', 'edit_columns' ), 0 );
add_action( 'init', array( 'mZoo\\Instructors\\MZOO_Instructors', 'display_columns' ), 0 );
add_action( 'init', array( 'mZoo\\Instructors\\MZOO_Instructors', 'tax_filters' ), 0 );
// Not sure what hook the following would need to filter, outside of Total theme environment.
//add_filter( 'wpex_image_sizes', array( 'mZoo\\Instructors\\MZOO_Instructors', 'instructor_image_sizes' ), 0 );
add_action( 'init', array( 'mZoo\\Instructors\\MZOO_Custom_Taxonomies', 'languages_init' ), 0 );
add_filter('archive_template', array('mZoo\\Instructors\\MZOO_Instructors', 'get_custom_template'));
add_filter('single_template', array('mZoo\\Instructors\\MZOO_Instructors', 'get_custom_template'));

if(!is_admin()){ // make sure the filters are only called in the frontend
        add_filter('get_post_tag', array( 'mZoo\\Instructors\\MZOO_Custom_Taxonomies', 'comma_tag_filter'));
        add_filter('get_terms', array( 'mZoo\\Instructors\\MZOO_Custom_Taxonomies', 'comma_tags_filter'));
        add_filter('get_the_terms', array( 'mZoo\\Instructors\\MZOO_Custom_Taxonomies', 'comma_tags_filter'));
        add_action( 'wp_enqueue_scripts', 'load_isotope' );
} else {
        add_action('add_meta_boxes', ['mZoo\\Instructors\\MZOO_Custom_Meta_Box', 'add']);
        add_action('save_post', ['mZoo\\Instructors\\MZOO_Custom_Meta_Box', 'save']);
}
// $metaboxes = new mZoo\MZOO_Custom_Meta_Box;

function load_isotope() {
    // scripts will load in footer
    mz_pr(MZOO_INSTRUCTOR_URL . 'js/jquery.isotope.min.js');
        wp_enqueue_script( 'isotope-js', MZOO_INSTRUCTOR_URL . 'js/jquery.isotope.min.js', array( 'jquery' ), true );
        wp_enqueue_script( 'instructor-js', MZOO_INSTRUCTOR_URL . 'js/instructor.js', array( 'jquery' ), true );
        wp_enqueue_style( 'instructor-css', MZOO_INSTRUCTOR_URL . 'css/instructor.css');
}


?>
