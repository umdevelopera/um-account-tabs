<?php
namespace um_ext\um_account_tabs\core;


if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class Account
 *
 * @package um_ext\um_account_tabs\core
 */
class Account {


	/**
	 * @var array
	 */
	protected $tabs = null;


	private $inited = false;


	/**
	 * Account constructor.
	 */
	public function __construct() {
		add_filter( 'um_account_page_default_tabs_hook', array( &$this, 'add_tabs' ), 100, 1 );
	}

	/**
	 * Get all custom account tabs like posts array.
	 *
	 * @return array Posts.
	 */
	public function get_tabs(){
		if ( ! is_array( $this->tabs ) ) {
			$this->tabs = get_posts(
				array(
					'post_type'      => 'um_account_tabs',
					'posts_per_page' => -1,
				)
			);
		}
		return $this->tabs;
	}


	/**
	 * Add custom account tabs.
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

			$icon     = empty( $tab->_icon ) ? 'um-icon-plus' : $tab->_icon;
			$position = absint( $tab->_position );

			// Add tab to menu.
			$tabs[ $position ][ $tab->post_name ] = array(
				'icon'         => $icon,
				'title'        => $tab->post_title,
				'custom'       => true,
				'show_button'  => ! empty( $tab->_um_form ),
				'submit_title' => __( 'Update', 'um-account-tabs' ),
			);

			// Show tab content.
			add_action( 'um_account_content_hook_' . $tab->post_name, function( $args ) use ( $tab ) {
				$output = '';

				$userdata     = get_userdata( get_current_user_id() );
				$placeholders = array(
					'{user_id}'                => get_current_user_id(),
					'{first_name}'             => $userdata->first_name,
					'{last_name}'              => $userdata->last_name,
					'{user_email}'             => $userdata->user_email,
					'{display_name}'           => $userdata->display_name,
					'[ultimatemember form_id=' => '[',
				);

				$tab_content = str_replace( array_keys( $placeholders ), array_values( $placeholders ), $tab->post_content );

				// Fix conflict that may appear if the tab contains Elementor template
				if ( class_exists( '\Elementor\Plugin' ) ) {
					\Elementor\Plugin::instance()->frontend->remove_content_filter();
					$output .= apply_filters( 'the_content', $tab_content );
					\Elementor\Plugin::instance()->frontend->add_content_filter();
				} else {
					$output .= apply_filters( 'the_content', $tab_content );
				}

				$output .= $this->um_custom_tab_form( $tab->ID, $tab->_um_form );

				return $output;
			} );

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
	 * Generate content for custom tabs
	 *
	 * @param  string $tab_id
	 * @param  int    $form_id
	 *
	 * @return string
	 */
	public function um_custom_tab_form( $tab_id, $form_id = 0 ) {
		if ( empty( $form_id ) ) {
			return '';
		}
		$user_id = get_current_user_id();

		// save profile settings.
		$set_id   = UM()->fields()->set_id;
		$set_mode = UM()->fields()->set_mode;
		$editing  = UM()->fields()->editing;
		$viewing  = UM()->fields()->viewing;

		// set profile settings.
		UM()->fields()->set_id   = $form_id;
		UM()->fields()->set_mode = get_post_meta( $form_id, '_um_mode', true );
		UM()->fields()->editing  = true;
		UM()->fields()->viewing  = false;

		$args = array(
			'form_id' => $form_id,
		);

		ob_start();
		//do_action( 'um_before_form', $args );
		do_action( 'um_before_profile_fields', $args );
		do_action( 'um_main_profile_fields', $args );
		do_action( 'um_after_form_fields', $args );
		//do_action( 'um_after_profile_fields', $args );
		?>
		<input type="hidden" name="is_signup" value="1">
		<input type="hidden" name="profile_nonce" value="<?php echo wp_create_nonce( 'um-profile-nonce' . $user_id ); ?>">
		<input type="hidden" name="user_id" value="<?php echo esc_attr( $user_id ); ?>">
		<?php


		$contents = ob_get_clean();

		// restore default account settings
		UM()->fields()->set_id   = $set_id;
		UM()->fields()->set_mode = $set_mode;
		UM()->fields()->editing  = $editing;
		UM()->fields()->viewing  = $viewing;

		return $contents;
	}
}
