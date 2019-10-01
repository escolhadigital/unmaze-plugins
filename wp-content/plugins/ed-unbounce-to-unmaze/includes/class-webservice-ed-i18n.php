<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       escolhadigital.com
 * @since      1.0.0
 *
 * @package    Webservice_Ed
 * @subpackage Webservice_Ed/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Webservice_Ed
 * @subpackage Webservice_Ed/includes
 * @author     Escolha Digital <geral@escolhadigital.com>
 */
class Webservice_Ed_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'webservice-ed',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
