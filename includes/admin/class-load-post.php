<?php
/**
 * Customize the "Account tab" post screen.
 *
 * @package um_ext\um_account_tabs\admin
 */

namespace um_ext\um_account_tabs\admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'um_ext\um_account_tabs\admin\Load_Post' ) ) {


	/**
	 * Class Load_Post.
	 *
	 * @package um_ext\um_account_tabs\admin
	 */
	class Load_Post {


		/**
		 * Load_Post constructor.
		 */
		public function __construct() {
			global $current_screen;
			if ( isset( $current_screen ) && 'um_account_tabs' === $current_screen->id ) {
				add_action( 'add_meta_boxes', array( &$this, 'add_metaboxes' ), 1 );
				add_action( 'save_post_um_account_tabs', array( &$this, 'save_metaboxes_data' ), 10, 3 );
			}
			add_filter( 'wp_insert_post_data', array( &$this, 'filter_post_data' ), 10, 4 );
		}


		/**
		 * Add metaboxes.
		 */
		public function add_metaboxes() {
			// don't show metaboxes for translations.
			if ( UM()->external_integrations()->is_wpml_active() ) {
				global $post, $sitepress;
				$tab_id = $sitepress->get_object_id( $post->ID, 'um_account_tabs', true, $sitepress->get_default_language() );
				if ( $tab_id && $tab_id !== $post->ID ) {
					return;
				}
			}

			add_meta_box(
				'um-admin-custom-account-tab/embed{' . um_account_tabs_path . '}',
				__( 'Embed content', 'um-account-tabs' ),
				array( UM()->metabox(), 'load_metabox_custom' ),
				'um_account_tabs',
				'normal',
				'default'
			);

			add_meta_box(
				'um-admin-custom-account-tab/access{' . um_account_tabs_path . '}',
				__( 'Restrictions', 'um-account-tabs' ),
				array( UM()->metabox(), 'load_metabox_custom' ),
				'um_account_tabs',
				'side',
				'default'
			);

			add_meta_box(
				'um-admin-custom-account-tab/appearance{' . um_account_tabs_path . '}',
				__( 'Appearance', 'um-account-tabs' ),
				array( UM()->metabox(), 'load_metabox_custom' ),
				'um_account_tabs',
				'side',
				'default'
			);
		}


		/**
		 * Validates the account tab name and slug to be not empty.
		 *
		 * @param  array $data                An array of slashed, sanitized, and processed post data.
		 * @param  array $postarr             An array of sanitized (and slashed) but otherwise unmodified post data.
		 * @param  array $unsanitized_postarr An array of slashed yet *unsanitized* and unprocessed post data.
		 * @param  bool  $update              Whether this is an existing post being updated.
		 * @return array
		 */
		public function filter_post_data( $data, $postarr, $unsanitized_postarr, $update ) {
			if ( isset( $data['post_type'] ) && 'um_account_tabs' === $data['post_type'] && isset( $unsanitized_postarr['post_status'] ) && 'auto-draft' !== $unsanitized_postarr['post_status'] ) {
				if ( empty( $data['post_title'] ) ) {
					$data['post_title'] = 'Account Tab';
				}
				if ( empty( $data['post_name'] ) ) {
					$tab_id            = empty( $unsanitized_postarr['ID'] ) ? time() : $unsanitized_postarr['ID'];
					$data['post_name'] = 'account-tab-' . $tab_id;
				}
			}
			return $data;
		}


		/**
		 * Get the post settings map.
		 *
		 * @return array
		 */
		public function get_settings_map(){
			return array(
				'_um_form'                 => array(
					'sanitize' => 'absint',
					'default'  => '',
				),
				'_um_form_header'          => array(
					'sanitize' => 'absint',
					'default'  => 0,
				),
				'_um_form_button'          => array(
					'sanitize' => 'sanitize_text_field',
					'default'  => __( 'Update', 'um-account-tabs' ),
				),
				'_can_have_this_tab_roles' => array(
					'sanitize' => 'wp_unslash',
					'default'  => '',
				),
				'_color'                   => array(
					'sanitize' => 'sanitize_hex_color',
					'default'  => '',
				),
				'_color_text'              => array(
					'sanitize' => 'sanitize_hex_color',
					'default'  => '',
				),
				'_icon'                    => array(
					'sanitize' => 'sanitize_text_field',
					'default'  => '',
				),
				'_position'                => array(
					'sanitize' => 'absint',
					'default'  => 800,
				),
				'_tab_slug'                => array(
					'sanitize' => 'sanitize_title',
					'default'  => '',
				),
			);
		}


		/**
		 * Save settings in metaboxes.
		 *
		 * @param int      $post_id Post ID.
		 * @param \WP_Post $post    Post object.
		 * @param bool     $update  Whether this is an existing post being updated.
		 */
		public function save_metaboxes_data( $post_id, $post, $update ) {
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				return;
			}
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			if ( empty( $_POST ) || empty( $_POST['um_account_tab'] ) ) {
				return;
			}
			check_admin_referer( 'update-post_' . $post_id );

			$input = map_deep( wp_unslash( $_POST['um_account_tab'] ), 'sanitize_text_field' );

			$form = isset( $input['_um_form'] ) ? absint( $input['_um_form'] ) : '';
			update_post_meta( $post_id, '_um_form', $form );

			$form_header = isset( $input['_um_form_header'] ) ? absint( $input['_um_form_header'] ) : 0;
			update_post_meta( $post_id, '_um_form_header', $form_header );

			$button = isset( $input['_um_form_button'] ) ? sanitize_text_field( $input['_um_form_button'] ) : __( 'Update', 'um-account-tabs' );
			update_post_meta( $post_id, '_um_form_button', $button );

			$roles = isset( $input['_can_have_this_tab_roles'] ) && is_array( $input['_can_have_this_tab_roles'] ) ? $input['_can_have_this_tab_roles'] : '';
			update_post_meta( $post_id, '_can_have_this_tab_roles', $roles );

			$color = isset( $input['_color'] ) ? sanitize_hex_color( $input['_color'] ) : '';
			update_post_meta( $post_id, '_color', $color );

			$color_text = isset( $input['_color_text'] ) ? sanitize_hex_color( $input['_color_text'] ) : '';
			update_post_meta( $post_id, '_color_text', $color_text );

			$icon = isset( $input['_icon'] ) ? sanitize_text_field( $input['_icon'] ) : '';
			update_post_meta( $post_id, '_icon', $icon );

			$position = isset( $input['_position'] ) ? absint( $input['_position'] ) : '';
			update_post_meta( $post_id, '_position', $position );

			$tab_slug = isset( $input['_tab_slug'] ) ? sanitize_title( $input['_tab_slug'] ) : '';
			update_post_meta( $post_id, '_tab_slug', $tab_slug );
		}
	}
}
