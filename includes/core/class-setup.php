<?php
namespace um_ext\um_account_tabs\core;


if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class Setup
 *
 * @package um_ext\um_account_tabs\core
 */
class Setup {


	/**
	 * Settings
	 *
	 * @var array
	 */
	var $settings_defaults;


	/**
	 * Setup constructor.
	 */
	function __construct() {
		$this->settings_defaults = array(
		);
	}


	/**
	 * Set Settings.
	 */
	function set_default_settings() {
		$options = get_option( 'um_options', array() );

		foreach ( $this->settings_defaults as $key => $value ) {
			if ( ! isset( $options[ $key ] ) ) {
				$options[ $key ] = $value;
			}
		}

		update_option( 'um_options', $options );
	}


	/**
	 * Run on plugin activation.
	 */
	function run_setup() {
		$this->set_default_settings();
	}
}