<?php
/*
Plugin Name: Ultimate Member - Account tabs
Description: Adds custom tabs to user account.
Version:     1.0.0
Author:      Ultimate Member support
Author URI:  https://ultimatemember.com/support/
Text Domain: um-account-tabs
Domain Path: /languages
UM version:  2.5.0
*/

if ( ! defined( 'ABSPATH' ) ) exit;

require_once( ABSPATH.'wp-admin/includes/plugin.php' );

$plugin_data = get_plugin_data( __FILE__ );

define( 'um_account_tabs_url', plugin_dir_url( __FILE__ ) );
define( 'um_account_tabs_path', plugin_dir_path( __FILE__ ) );
define( 'um_account_tabs_plugin', plugin_basename( __FILE__  ) );
define( 'um_account_tabs_extension', $plugin_data['Name'] );
define( 'um_account_tabs_version', $plugin_data['Version'] );
define( 'um_account_tabs_textdomain', 'um-account-tabs' );
define( 'um_account_tabs_requires', '2.5.0' );


if ( ! function_exists( 'um_account_tabs_plugins_loaded' ) ) {
	function um_account_tabs_plugins_loaded() {
		$locale = ( get_locale() != '' ) ? get_locale() : 'en_US';
		load_textdomain( um_account_tabs_textdomain, WP_LANG_DIR . '/plugins/' . um_account_tabs_textdomain . '-' . $locale . '.mo' );
		load_plugin_textdomain( um_account_tabs_textdomain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}
add_action( 'plugins_loaded', 'um_account_tabs_plugins_loaded', 0 );


add_action( 'plugins_loaded', 'um_account_tabs_check_dependencies', -20 );

if ( ! function_exists( 'um_account_tabs_check_dependencies' ) ) {
	function um_account_tabs_check_dependencies() {
		if ( ! defined( 'um_path' ) || ! file_exists( um_path  . 'includes/class-dependencies.php' ) ) {
			// UM is not installed.
			function um_account_tabs_dependencies() {
				echo '<div class="error"><p>' . sprintf( __( 'The <strong>%s</strong> extension requires the Ultimate Member plugin to be activated to work properly. You can download it <a href="https://wordpress.org/plugins/ultimate-member">here</a>', 'um-user-photos' ), um_account_tabs_extension ) . '</p></div>';
			}

			add_action( 'admin_notices', 'um_account_tabs_dependencies' );

		} else {

			if ( ! function_exists( 'UM' ) ) {
				require_once um_path . 'includes/class-dependencies.php';
				$is_um_active = um\is_um_active();
			} else {
				$is_um_active = UM()->dependencies()->ultimatemember_active_check();
			}

			if ( ! $is_um_active ) {
				// UM is not active.
				function um_account_tabs_dependencies() {
					echo '<div class="error"><p>' . sprintf( __( 'The <strong>%s</strong> extension requires the Ultimate Member plugin to be activated to work properly. You can download it <a href="https://wordpress.org/plugins/ultimate-member">here</a>', 'um-user-photos' ), um_account_tabs_extension ) . '</p></div>';
				}

				add_action( 'admin_notices', 'um_account_tabs_dependencies' );

			} else {
				require_once um_account_tabs_path . 'includes/core/um-account-tabs-init.php';
			}
		}
	}
}


register_activation_hook( um_account_tabs_plugin, 'um_account_tabs_activation_hook' );
if ( ! function_exists( 'um_account_tabs_activation_hook' ) ) {
	function um_account_tabs_activation_hook() {
		// first install.
		$version = get_option( 'um_account_tabs_version' );
		if ( ! $version ) {
			update_option( 'um_account_tabs_last_version_upgrade', um_account_tabs_version );
		}

		if ( $version != um_account_tabs_version ) {
			update_option( 'um_account_tabs_version', um_account_tabs_version );
		}

		// run setup.
		if ( ! class_exists( 'um_ext\um_account_tabs\core\Setup' ) ) {
			require_once um_account_tabs_path . 'includes/core/class-setup.php';
		}

		$setup = new um_ext\um_account_tabs\core\Setup();
		$setup->run_setup();
	}
}