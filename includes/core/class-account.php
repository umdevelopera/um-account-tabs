<?php
namespace um_ext\um_account_tabs\core;

defined( 'ABSPATH' ) || exit;

/**
 * Modifies the Account page.
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
	 * True if the embedded profile header has been shown.
	 *
	 * @var bool
	 */
	private $is_profile_header_shown = false;

	/**
	 * True if the embedded profile form has been shown.
	 *
	 * @var bool
	 */
	private $is_profile_form_shown = false;

	/**
	 * Account constructor.
	 */
	public function __construct() {

		// Add custom account tabs.
		add_filter( 'um_account_page_default_tabs_hook', array( $this, 'add_tabs' ), 100, 1 );

		// Print styles to customize account tabs.
		add_action( 'um_after_account_page_load', array( $this, 'print_styles' ), 30, 1 );
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_script' ) );

		// Redirect to the same page after updating the profile form in account.
		add_filter( 'um_update_profile_redirect_after', array( $this, 'update_profile_redirect' ), 10, 3 );

		// Placeholders.
		add_filter( 'um_template_tags_patterns_hook', array( $this, 'add_placeholder' ) );
		add_filter( 'um_template_tags_replaces_hook', array( $this, 'add_replace_placeholder' ) );
	}


	/**
	 * UM Placeholders.
	 *
	 * @param array $placeholders
	 *
	 * @return array
	 */
	public function add_placeholder( $placeholders ) {
		$placeholders[] = '{user_id}';
		$placeholders[] = '{user_role}';
		$placeholders[] = '{admin_email}';
		$placeholders[] = '{site_url}';
		$placeholders[] = '{user_profile_link}';
		$placeholders[] = '{user_avatar}';
		return $placeholders;
	}


	/**
	 * UM Replace Placeholders.
	 *
	 * @param array $replace_placeholders
	 *
	 * @return array
	 */
	public function add_replace_placeholder( $replace_placeholders ) {
		$replace_placeholders[] = um_user( 'ID' );
		$replace_placeholders[] = um_user( 'role' );
		$replace_placeholders[] = um_admin_email();
		$replace_placeholders[] = get_bloginfo( 'url' );
		$replace_placeholders[] = um_user_profile_url();
		$replace_placeholders[] = get_avatar( um_user( 'ID' ), 190 );
		return $replace_placeholders;
	}


	/**
	 * Get all custom account tabs like posts array.
	 *
	 * @return array Posts.
	 */
	public function get_tabs() {
		if ( ! is_array( $this->tabs ) ) {
			$args = array(
				'numberposts' => -1,
				'post_status' => 'publish',
				'post_type'   => 'um_account_tabs',
			);
			$tabs = get_posts( $args );

			/**
			 * Hook: um_account_tabs_get_tabs
			 * Type: filter
			 * Description: Filter custom account tabs. May be used to localize them.
			 *
			 * @since 1.0.7
			 *
			 * @param array $tabs All custom account tabs like posts array.
			 */
			$filtered_tabs = apply_filters( 'um_account_tabs_get_tabs', $tabs );

			$this->tabs = array();
			foreach( $filtered_tabs as $tab ){
				$tab_slug                = $tab->_tab_slug ? sanitize_title( $tab->_tab_slug ) : $tab->post_name;
				$this->tabs[ $tab_slug ] = $tab;
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
			while ( array_key_exists( $position, $tabs ) ) {
				$position++;
			}
			$submit_title = $tab->_um_form_button ? wp_strip_all_tags( $tab->_um_form_button ) : __( 'Update', 'um-account-tabs' );
			$tab_slug     = $tab->_tab_slug ? sanitize_title( $tab->_tab_slug ) : $tab->post_name;

			// Add tab to menu.
			$tabs[ $position ][ $tab_slug ] = array(
				'icon'         => empty( $tab->_icon ) ? 'um-icon-plus' : $tab->_icon,
				'title'        => $tab->post_title,
				'color'        => $tab->_color,
				'custom'       => true,
				'show_button'  => ! empty( $tab->_um_form ),
				'submit_title' => $submit_title,
			);

			// Show tab content.
			add_filter( 'um_account_content_hook_' . $tab_slug, array( $this, 'display_tab_content' ), 10, 2 );
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
			$tab         = $this->tabs[ $tab_id ];
			$content     = wpautop( $tab->post_content );
			$tab_content = um_convert_tags( $content, array(), false );

			if ( class_exists( '\Elementor\Plugin' ) ) {
				\Elementor\Plugin::instance()->frontend->remove_content_filter();
			}
			$output = apply_filters( 'the_content', $tab_content );
			if ( class_exists( '\Elementor\Plugin' ) ) {
				\Elementor\Plugin::instance()->frontend->add_content_filter();
			}

			/**
			 * Hook: um_account_tabs_sanitize_tab
			 * Type: filter
			 * Description: Turn on/off the custom tab content sanitize.
			 *
			 * @since 1.1.5
			 *
			 * @param bool   $sanitize_tab Set `true` to sanitize the tab content.
			 * @param string $tab_id       Tab slug.
			 */
			$sanitize_tab = apply_filters( 'um_account_tabs_sanitize_tab', true, $tab_id );
			if ( $sanitize_tab ) {
				$output = wp_kses( $output, 'post' );
			}

			if ( ! empty( $tab->_um_form ) ) {
				$output .= $this->display_embeded_form( $tab_id, $tab->_um_form );
			}
		}

		// Restore global user if it was changed.
		if ( um_user( 'ID' ) !== get_current_user_id() ) {
			um_fetch_user( get_current_user_id() );
		}

		return $output;
	}


	/**
	 * Generate content for custom tabs.
	 *
	 * @param string $tab_id Tab key.
	 * @param int    $form_id Form ID.
	 *
	 * @return string
	 */
	public function display_embeded_form( $tab_id, $form_id = 0 ) {
		if ( empty( $form_id ) ) {
			return '';
		}
		$this->is_profile_form_shown = true;

		$tab     = $this->tabs[ $tab_id ];
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
		$post                    = get_post( um_get_predefined_page_id( 'user' ) );
		UM()->fields()->set_id   = $form_id;
		UM()->fields()->set_mode = 'profile';
		UM()->fields()->editing  = true;
		UM()->fields()->viewing  = false;

		$classes = UM()->shortcodes()->get_class( 'profile' );
		ob_start();
		?>
		<div class="um <?php echo esc_attr( $classes ); ?> um-<?php echo absint( $form_id ); ?> um-role-<?php echo esc_attr( um_user( 'role' ) ); ?> ">
			<div class="um-form" data-mode="profile" data-form_id="<?php echo absint( $form_id ); ?>">
				<?php
				if ( $tab->_um_form_header && false === $this->is_profile_header_shown ) {
					// if "Display the profile header" is turned ON.

					$this->is_profile_header_shown = true;
					do_action( 'um_profile_header_cover_area', $args );
					do_action( 'um_profile_header', $args );
				}
				if ( ! $tab->_um_form_header || $tab->_um_form_fields ) {
					// if "Display the profile header" is turned OFF or "Display the profile fields" is turned ON.

					do_action( 'um_before_profile_fields', $args );
					do_action( 'um_main_profile_fields', $args );
					do_action( 'um_after_form_fields', $args );
				}
				?>
				<input type="hidden" name="is_signup" value="1">
				<input type="hidden" name="profile_nonce" value="<?php echo esc_attr( wp_create_nonce( 'um-profile-nonce' . $user_id ) ); ?>">
				<input type="hidden" name="user_id" value="<?php echo esc_attr( $user_id ); ?>">
			</div>
		</div>
		<?php

		$contents = ob_get_clean();

		// restore account settings.
		$post                     = $global_post;
		UM()->fields()->set_id    = $set_id;
		UM()->fields()->set_mode  = $set_mode;
		UM()->fields()->editing   = $editing;
		UM()->fields()->viewing   = $viewing;
		UM()->form()->nonce       = $form_nonce;
		UM()->form()->form_suffix = $form_suffix;

		return $contents;
	}


	/**
	 * Enqueue scripts.
	 */
	public function enqueue_script() {
		if ( um_is_core_page( 'account' ) ) {
			wp_enqueue_script(
				'um-account-tabs',
				um_account_tabs_url . 'assets/js/um-account-tabs.js',
				array( 'jquery' ),
				um_account_tabs_version,
				true
			);
		}
	}


	/**
	 * Print styles to customize account tabs.
	 */
	public function print_styles() {
		$css = '';
		foreach ( $this->get_tabs() as $tab_id => $tab ) {
			if ( ! empty( $tab->_color ) ) {
				$css .= '.um-account .um-form .um-account-side li a.um-account-link[data-tab="' . esc_attr( $tab_id ) . '"]'
					. '{ background-color: ' . esc_attr( $tab->_color ) . '; }' . "\n";
				$css .= '.um-account .um-form .um-account-side li a.um-account-link[data-tab="' . esc_attr( $tab_id ) . '"].current:not(:hover)'
					. '{ box-shadow: inset 0px 0px 2em 2em rgba(255,255,255,0.1); }' . "\n";
				$css .= '.um-account .um-form .um-account-side li a.um-account-link[data-tab="' . esc_attr( $tab_id ) . '"]:hover:not(.current)'
					. '{ box-shadow: inset 0px 0px 2em 2em rgba(0,0,0,0.1); }' . "\n";
			}
			if ( ! empty( $tab->_color_text ) ) {
				$css .= '.um-account .um-form .um-account-side li a.um-account-link[data-tab="' . esc_attr( $tab_id ) . '"],'
					. '.um-account .um-form .um-account-side li a.um-account-link[data-tab="' . esc_attr( $tab_id ) . '"] span.um-account-icon,'
					. '.um-account .um-form .um-account-side li a.um-account-link[data-tab="' . esc_attr( $tab_id ) . '"] span.um-account-title'
					. '{ color: ' . esc_attr( $tab->_color_text ) . ' !important; }' . "\n";
			}
		}
		if ( $this->is_profile_header_shown ) {
			$css .= '.um-account-tab .um-cover'
				. '{margin-top: 15px;}' . "\n";
			$css .= '.um-account-tab .um-profile-headericon'
				. '{display: none;}' . "\n";
		}
		if ( $this->is_profile_form_shown ) {
			$css .= '.um-account-tab .um.um-profile'
				. '{padding-bottom: 0;}' . "\n";
		}
		if ( $css ) {
			?><style type="text/css"><?php echo "\n" . trim( $css ) . "\n"; ?></style><?php
		}
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
			$url = remove_query_arg( 'um_action', UM()->permalinks()->get_current_url() );
			if ( empty( UM()->form()->errors ) ) {
				$url = add_query_arg( 'updated', 'account', $url );
			} else {
				$url = add_query_arg( 'err', 'account', $url );
			}
		}
		return $url;
	}
}
