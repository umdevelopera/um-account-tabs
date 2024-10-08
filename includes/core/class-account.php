<?php
/**
 * Modifies the Account page.
 *
 * @package um_ext\um_account_tabs\core
 */

namespace um_ext\um_account_tabs\core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Account.
 *
 * @package um_ext\um_account_tabs\core
 */
class Account {


	/**
	 * Custom tabs.
	 *
	 * @var array
	 */
	protected $tabs = null;


	/**
	 * Account constructor.
	 */
	public function __construct() {

		// Add custom account tabs.
		add_filter( 'um_account_page_default_tabs_hook', array( $this, 'add_tabs' ), 100, 1 );

		// Print styles to customize account tabs.
		add_action( 'um_after_account_page_load', array( $this, 'print_styles' ), 30, 1 );

		// Redirect to the same page after updating the profile form in account.
		add_filter( 'um_update_profile_redirect_after', array( $this, 'update_profile_redirect' ), 10, 3 );
	}

	/**
	 * Get all custom account tabs like posts array.
	 *
	 * @return array Posts.
	 */
	public function get_tabs() {
		if ( ! is_array( $this->tabs ) ) {
			$args = array(
				'post_type'      => 'um_account_tabs',
				'posts_per_page' => -1,
			);
			$tabs = get_posts( $args );

			$this->tabs = array();
			foreach( $tabs as $tab ){
				$this->tabs[ $tab->post_name ] = $tab;
			}
		}
		return $this->tabs;
	}


	/**
	 * Adds custom account tabs.
	 *
	 * @param array $tabs All account tabs.
	 *
	 * @return array
	 */
	public function add_tabs( $tabs ) {
		foreach ( $this->get_tabs() as $tab ) {
			if ( ! $this->can_have_tab( $tab->ID ) ) {
				continue;
			}

			$position = absint( $tab->_position );

			// Add tab to menu.
			$tabs[ $position ][ $tab->post_name ] = array(
				'icon'         => empty( $tab->_icon ) ? 'um-icon-plus' : $tab->_icon,
				'title'        => $tab->post_title,
				'color'        => $tab->_color,
				'custom'       => true,
				'show_button'  => ! empty( $tab->_um_form ),
				'submit_title' => __( 'Update', 'um-account-tabs' ),
			);

			// Show tab content.
			add_filter( 'um_account_content_hook_' . $tab->post_name, array( $this, 'display_tab_content' ), 10, 2 );
		}
		return $tabs;
	}


	/**
	 * Check if user has the current tab by role.
	 *
	 * @param string $tab_id Tab key.
	 *
	 * @return bool
	 */
	public function can_have_tab( $tab_id ) {

		$can_have = get_post_meta( $tab_id, '_can_have_this_tab_roles', true );
		if ( empty( $can_have ) ) {
			return true;
		}

		$current_user_roles = UM()->roles()->get_all_user_roles( get_current_user_id() );
		if ( ! is_array( $current_user_roles ) ) {
			$current_user_roles = array();
		}

		if ( array_intersect( $current_user_roles, $can_have ) ) {
			return true;
		}

		return false;
	}


	/**
	 * Displays custom tab content.
	 *
	 * Hook: um_account_content_hook_{$id}
	 *
	 * @see \um\core\Account::get_tab_fields()
	 *
	 * @since 1.0.5
	 *
	 * @param string $output         Account tab Output.
	 * @param array  $shortcode_args Account shortcode arguments.
	 *
	 * @return string
	 */
	public function display_tab_content( $output = '', $shortcode_args = array() ) {

		$hook_name = current_filter();
		$tab_id    = str_replace( 'um_account_content_hook_', '', $hook_name );

		if ( $tab_id && array_key_exists( $tab_id, $this->tabs ) ) {
			$tab = $this->tabs[ $tab_id ];

			$userdata     = wp_get_current_user();
			$placeholders = array(
				'{user_id}'                => $userdata->ID,
				'{first_name}'             => $userdata->first_name,
				'{last_name}'              => $userdata->last_name,
				'{user_email}'             => $userdata->user_email,
				'{display_name}'           => $userdata->display_name,
				'[ultimatemember form_id=' => '[',
			);

			$tab_content = strtr( $tab->post_content, $placeholders );

			// Fix conflict that may appear if the tab contains Elementor template.
			if ( class_exists( '\Elementor\Plugin' ) ) {
				\Elementor\Plugin::instance()->frontend->remove_content_filter();
				$output = apply_filters( 'the_content', $tab_content );
				\Elementor\Plugin::instance()->frontend->add_content_filter();
			} else {
				$output = apply_filters( 'the_content', $tab_content );
			}

			if ( ! empty( $tab->_um_form ) ) {
				$output .= $this->um_custom_tab_form( $tab->ID, $tab->_um_form );
			}
		}

		return $output;
	}


	/**
	 * Print styles to customize account tabs.
	 */
	public function print_styles() {
		$colors = array();
		foreach ( $this->get_tabs() as $tab_id => $tab ) {
			if ( ! empty( $tab->_color ) ) {
				$colors[ $tab_id ] = $tab->_color;
			}
		}
		if ( $colors ) {
			?><style type="text/css"><?php
			foreach ( $colors as $tab_id => $color ) {
				echo "\n";
				?>.um-account .um-account-side a.um-account-link[data-tab="<?php echo esc_attr( $tab_id ); ?>"] { background-color: <?php echo esc_attr( $color ); ?>; }<?php
				echo "\n";
				?>.um-account .um-account-side a.um-account-link[data-tab="<?php echo esc_attr( $tab_id ); ?>"]:hover { box-shadow: inset 0px 0px 2em 2em rgba(0,0,0,0.05); }<?php
			}
			?></style><?php
		}
	}


	/**
	 * Generate content for custom tabs.
	 *
	 * @param string $tab_id  Tab ID.
	 * @param int    $form_id Form ID.
	 *
	 * @return string
	 */
	public function um_custom_tab_form( $tab_id, $form_id = 0 ) {
		if ( empty( $form_id ) ) {
			return '';
		}
		$args    = UM()->query()->post_data( $form_id );
		$user_id = get_current_user_id();

		// save account settings.
		global $post;
		$global_post = $post;
		$set_id      = UM()->fields()->set_id;
		$set_mode    = UM()->fields()->set_mode;
		$editing     = UM()->fields()->editing;
		$viewing     = UM()->fields()->viewing;
		$form_nonce  = UM()->form()->nonce;
		$form_suffix = UM()->form()->form_suffix;

		// set profile settings.
		$post = get_post( absint( UM()->config()->permalinks[ 'user' ] ) );
		UM()->fields()->set_id   = $form_id;
		UM()->fields()->set_mode = get_post_meta( $form_id, '_um_mode', true );
		UM()->fields()->editing  = true;
		UM()->fields()->viewing  = false;

		ob_start();
		do_action( 'um_before_profile_fields', $args );
		do_action( 'um_main_profile_fields', $args );
		do_action( 'um_after_form_fields', $args );

		?>
		<input type="hidden" name="is_signup" value="1">
		<input type="hidden" name="profile_nonce" value="<?php echo esc_attr( wp_create_nonce( 'um-profile-nonce' . $user_id ) ); ?>">
		<input type="hidden" name="user_id" value="<?php echo esc_attr( $user_id ); ?>">
		<?php

		$contents = ob_get_clean();

		// restore account settings.
		$post = $global_post;
		UM()->fields()->set_id    = $set_id;
		UM()->fields()->set_mode  = $set_mode;
		UM()->fields()->editing   = $editing;
		UM()->fields()->viewing   = $viewing;
		UM()->form()->nonce       = $form_nonce;
		UM()->form()->form_suffix = $form_suffix;

		return $contents;
	}


	/**
	 * Redirect to the same page after updating the profile form in account.
	 *
	 * @since 1.0.2
	 *
	 * @param string $url     Redirect URL.
	 * @param int    $user_id User ID.
	 * @param array  $args    Form data.
	 *
	 * @return string
	 */
	public function update_profile_redirect( $url, $user_id, $args ) {
		if ( is_array( $args ) && isset( $args['_um_account'] ) && isset( $args['_um_account_tab'] ) ) {
			$current_url = UM()->permalinks()->get_current_url();
			$url         = remove_query_arg( 'um_action', $current_url );
		}
		return $url;
	}
}
