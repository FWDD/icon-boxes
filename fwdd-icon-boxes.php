<?php
/**
 * FWDD Icon Boxes
 *
 * @package   FWDD_Icon_Boxes
 * @license   GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: FWDD Icon Boxes
 * Plugin URI:  https://github.com/FWDD/icon-boxes
 * Description: Extends the text widget to add support for FontAwesome Icons and button links.
 * Version:     0.1.0
 * Author:      FWDD
 * Author URI:  https://freelance-web-designer-developer.com/
 * Text Domain: fwdd-icon-boxes
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

//Define the path of this plugin.
define( 'FWDD_ICON_BOXES_DIR', plugin_dir_path( __FILE__ ) );
//Require list of icons
require_once( FWDD_ICON_BOXES_DIR . 'includes/icon-array.php' );
//Require the widget class
require_once( FWDD_ICON_BOXES_DIR . 'includes/icon-box-widget.php' );

/**
 * Widget Registration.
 *
 * Register Service Boxes Widgets Text Icon.
 *
 */
function fwdd_load_widget() {

	register_widget( 'FWDD_Icon_Boxes' );

}
add_action( 'widgets_init', 'fwdd_load_widget' );

/**
 * Load Javascript and CSS files only if on widgets page.
 * @param string $hook The hook suffix of the current page
 */
function fwdd_Icon_boxes_admin($hook) {
	if ( 'widgets.php' != $hook ){
		return;
	}
	wp_register_style( "fontawesome", "//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css", array(), "4.5.0", "all" );
	wp_enqueue_style( 'fontawesome' );
	wp_register_style( "chosen", plugins_url('css/chosen.css', __FILE__) );
	wp_enqueue_style( 'chosen' );
	wp_register_script( 'chosen', plugins_url('/js/chosen.jquery.min.js', __FILE__), array( 'jquery' ), '1.5.1', true );
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'fwdd-icons', plugins_url('/js/fwdd-icons.js', __FILE__), array( 'chosen', 'iris' ), false, true );
}

add_action( 'admin_enqueue_scripts', 'fwdd_Icon_boxes_admin' );
