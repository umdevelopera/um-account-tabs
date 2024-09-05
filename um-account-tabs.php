<?php
/**
 * Plugin Name: Ultimate Member - Account tabs
 * Plugin URI:  https://github.com/umdevelopera/um-account-tabs
 * Description: Adds custom tabs to the Account page menu
 * Author:      umdevelopera
 * Author URI:  https://github.com/umdevelopera
 * Text Domain: um-account-tabs
 * Domain Path: /languages
 *
 * Version: 1.0.6
 * UM version: 2.8.0
 * Requires at least: 5.5
 * Requires PHP: 5.6
 *
 * @package UM Tools
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
define( 'um_account_tabs_requires', '2.7.0' );


// Check dependencies.
if ( ! function_exists( 'um_account_tabs_check_dependencies' ) ) {
	function um_account_tabs_check_dependencies() {
		if ( ! defined( 'um_path' ) || ! function_exists( 'UM' ) || ! UM()->dependencies()->ultimatemember_active_check() ) {
			// Ultimate Member is not active.
			add_action(
				'admin_notices',
				function () {
					// translators: %s - plugin name.
					echo '<div class="error"><p>' . wp_kses_post( sprintf( __( 'The <strong>%s</strong> extension requires the Ultimate Member plugin to be activated to work properly. You can download it <a href="https://wordpress.org/plugins/ultimate-member">here</a>', 'um-account-tabs' ), um_account_tabs_extension ) ) . '</p></div>';
				}
			);
		} else {
			require_once 'includes/core/class-um-account-tabs.php';
			UM()->set_class( 'Account_Tabs', true );
		}
	}
}
add_action( 'plugins_loaded', 'um_account_tabs_check_dependencies', 2 );
