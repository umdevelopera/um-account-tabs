<?php
/**
	Plugin Name: Ultimate Member - Account tabs
	Plugin URI:  https://github.com/umdevelopera/um-account-tabs
	Description: Adds custom tabs to the user account.
	Version:     1.0.2
	Author:      umdevelopera
	Author URI:  https://github.com/umdevelopera
	Text Domain: um-account-tabs
	Domain Path: /languages
	UM version:  2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$plugin_data = get_plugin_data( __FILE__ );

define( 'um_account_tabs_url', plugin_dir_url( __FILE__ ) );
define( 'um_account_tabs_path', plugin_dir_path( __FILE__ ) );
define( 'um_account_tabs_plugin', plugin_basename( __FILE__ ) );
define( 'um_account_tabs_extension', $plugin_data['Name'] );
define( 'um_account_tabs_version', $plugin_data['Version'] );
define( 'um_account_tabs_textdomain', 'um-account-tabs' );
define( 'um_account_tabs_requires', '2.5.0' );

// Activation script.
if ( ! function_exists( 'um_account_tabs_activation_hook' ) ) {
	function um_account_tabs_activation_hook() {
		$version = get_option( 'um_account_tabs_version' );
		if ( ! $version ) {
			update_option( 'um_account_tabs_last_version_upgrade', um_account_tabs_version );
		}
		if ( um_account_tabs_version !== $version ) {
			update_option( 'um_account_tabs_version', um_account_tabs_version );
		}
	}
}
register_activation_hook( um_account_tabs_plugin, 'um_account_tabs_activation_hook' );

// Check dependencies.
if ( ! function_exists( 'um_account_tabs_check_dependencies' ) ) {
	function um_account_tabs_check_dependencies() {
		if ( ! defined( 'um_path' ) || ! function_exists( 'UM' ) || ! UM()->dependencies()->ultimatemember_active_check() ) {
			// UM is not active.
			add_action(
				'admin_notices',
				function () {
					// translators: %s - plugin name.
					echo '<div class="error"><p>' . wp_kses_post( sprintf( __( 'The <strong>%s</strong> extension requires the Ultimate Member plugin to be activated to work properly. You can download it <a href="https://wordpress.org/plugins/ultimate-member">here</a>', 'um-account-tabs' ), um_account_tabs_extension ) ) . '</p></div>';
				}
			);
		} else {
			require_once 'includes/core/class-um-account-tabs.php';

			function um_account_tabs_init() {
				if ( function_exists( 'UM' ) ) {
					UM()->set_class( 'Account_Tabs', true );
				}
			}
			add_action( 'plugins_loaded', 'um_account_tabs_init', -10, 1 );
		}
	}
}
add_action( 'plugins_loaded', 'um_account_tabs_check_dependencies', -20 );

// Loads a plugin's translated strings.
if ( ! function_exists( 'um_account_tabs_plugins_loaded' ) ) {
	function um_account_tabs_plugins_loaded() {
		$locale = ( get_locale() !== '' ) ? get_locale() : 'en_US';
		load_textdomain( um_account_tabs_textdomain, WP_LANG_DIR . '/plugins/' . um_account_tabs_textdomain . '-' . $locale . '.mo' );
		load_plugin_textdomain( um_account_tabs_textdomain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}
add_action( 'plugins_loaded', 'um_account_tabs_plugins_loaded', 0 );
