<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://tekniskera.com/
 * @since      1.0.0
 *
 * @package    Tewms
 * @subpackage Tewms/admin
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    Tewms
 * @subpackage Tewms/admin
 * @author     Teknisk Era  <info@tekniskera.com>
 */
class Tewms_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function tewms_load_plugin_textdomain() {

		load_plugin_textdomain(
			'wholesale-market-suite-for-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}