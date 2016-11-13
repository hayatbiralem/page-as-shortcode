<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://hayatbiralem.com
 * @since             1.0.0
 * @package           Page_As_Shortcode
 *
 * @wordpress-plugin
 * Plugin Name:       Page as Shortcode
 * Plugin URI:        http://hayatbiralem.com/plugins/page-as-shortcode
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Ömür Yanıkoğlu
 * Author URI:        http://hayatbiralem.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       page-as-shortcode
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-page-as-shortcode-activator.php
 */
function activate_page_as_shortcode() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-page-as-shortcode-activator.php';
	Page_As_Shortcode_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-page-as-shortcode-deactivator.php
 */
function deactivate_page_as_shortcode() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-page-as-shortcode-deactivator.php';
	Page_As_Shortcode_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_page_as_shortcode' );
register_deactivation_hook( __FILE__, 'deactivate_page_as_shortcode' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-page-as-shortcode.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_page_as_shortcode() {

	$plugin = new Page_As_Shortcode();
	$plugin->run();

}
run_page_as_shortcode();
