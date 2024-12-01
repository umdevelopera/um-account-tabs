<?php
/**
 * Adds the Account Tabs sumbenu to the Ultimate Member admin menu.
 *
 * @package um_ext\um_account_tabs\admin
 */

namespace um_ext\um_account_tabs\admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'um_ext\um_account_tabs\admin\Admin' ) ) {


	/**
	 * Class Admin.
	 *
	 * @package um_ext\um_account_tabs\admin
	 */
	class Admin {


		/**
		 * Admin constructor.
		 */
		public function __construct() {
			// Menu.
			add_action( 'admin_menu', array( &$this, 'create_admin_submenu' ), 1001 );

			// Edit screen.
			add_action( 'load-edit.php', array( $this, 'load_edit' ) );

			// Post screen.
			add_action( 'load-post.php', array( $this, 'load_post' ), 9 );
			add_action( 'load-post-new.php', array( $this, 'load_post' ), 9 );

			add_filter( 'um_is_ultimatememeber_admin_screen', array( &$this, 'is_um_screen' ), 10, 1 );
		}


		/**
		 * Add submenu for Account Tabs.
		 */
		public function create_admin_submenu() {
			add_submenu_page(
				'ultimatemember',
				__( 'Account Tabs', 'um-account-tabs' ),
				__( 'Account Tabs', 'um-account-tabs' ),
				'manage_options',
				'edit.php?post_type=um_account_tabs'
			);
		}


		/**
		 * Extends UM admin pages for enqueue scripts.
		 *
		 * @param  bool $is_um Whether this screen is a part of the Ultimate Member.
		 *
		 * @return bool
		 */
		public function is_um_screen( $is_um ) {
			global $current_screen;
			if ( ! empty( $current_screen ) && strstr( $current_screen->id, 'um_account_tabs' ) ) {
				$is_um = true;
			}
			return $is_um;
		}


		/**
		 * Customize the "Account tabs" table.
		 *
		 * @return um_ext\um_account_tabs\admin\Load_Edit()
		 */
		public function load_edit() {
			if ( empty( UM()->classes['um_account_load_edit'] ) ) {
				require_once um_account_tabs_path . 'includes/admin/class-load-edit.php';
				UM()->classes['um_account_load_edit'] = new Load_Edit();
			}
			return UM()->classes['um_account_load_edit'];
		}


		/**
		 * Customize the "Account tab" post screen.
		 *
		 * @return um_ext\um_account_tabs\admin\Load_Post()
		 */
		public function load_post() {
			if ( empty( UM()->classes['um_account_load_post'] ) ) {
				require_once um_account_tabs_path . 'includes/admin/class-load-post.php';
				UM()->classes['um_account_load_post'] = new Load_Post();
			}
			return UM()->classes['um_account_load_post'];
		}
	}
}
