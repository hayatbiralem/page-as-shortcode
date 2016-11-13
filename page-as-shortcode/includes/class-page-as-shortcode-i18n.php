<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://hayatbiralem.com
 * @since      1.0.0
 *
 * @package    Page_As_Shortcode
 * @subpackage Page_As_Shortcode/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Page_As_Shortcode
 * @subpackage Page_As_Shortcode/includes
 * @author     Ömür Yanıkoğlu <hayatbiralem@gmail.com>
 */
class Page_As_Shortcode_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'page-as-shortcode',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
