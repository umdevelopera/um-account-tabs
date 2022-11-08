<?php
namespace um_ext\um_account_tabs\core;
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class Common
 *
 * @package um_ext\um_account_tabs\core
 */
class Common {


	/**
	 * Common constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ), 2 );
	}


	/**
	 * Register custom post type.
	 */
	public function register_post_type() {
		$labels = [
			'name'              => _x( 'Account tabs', 'Post Type General Name', 'um-account-tabs' ),
			'singular_name'     => _x( 'Account tab', 'Post Type Singular Name', 'um-account-tabs' ),
			'menu_name'         => __( 'Account Tabs', 'um-account-tabs' ),
			'all_items'         => __( 'All Tabs', 'um-account-tabs' ),
			'add_new_item'      => __( 'Add New Tab', 'um-account-tabs' ),
			'add_new'           => __( 'Add New', 'um-account-tabs' ),
			'new_item'          => __( 'New Tab', 'um-account-tabs' ),
			'edit_item'         => __( 'Edit Tab', 'um-account-tabs' ),
			'update_item'       => __( 'Update Tab', 'um-account-tabs' ),
			'view_item'         => __( 'View Tab', 'um-account-tabs' ),
			'view_items'        => __( 'View Tabs', 'um-account-tabs' ),
		];

		$args = [
			'label'                 => __( 'Account Tabs', 'um-account-tabs' ),
			'labels'                => $labels,
			'hierarchical'          => false,
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => false,
			'show_in_admin_bar'     => false,
			'show_in_nav_menus'     => false,
			'can_export'            => false,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'capability_type'       => 'page',
			'supports'              => array( 'title', 'editor' ),
		];

		register_post_type( 'um_account_tabs', $args );
	}
}