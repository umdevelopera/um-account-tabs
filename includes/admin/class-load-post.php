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
		 * @since version 1.1.4
		 *
		 * @return array
		 */
		public function get_settings_map() {

			return array(
				'_um_form'                 => array(
					'default'  => '',
					'sanitize' => 'absint',
				),
				'_um_form_header'          => array(
					'default'  => 0,
					'sanitize' => 'absint',
				),
				'_um_form_fields'          => array(
					'default'  => 0,
					'sanitize' => 'absint',
				),
				'_um_form_button'          => array(
					'default'  => __( 'Update', 'um-account-tabs' ),
					'sanitize' => 'sanitize_text_field',
				),
				'_can_have_this_tab_roles' => array(
					'default'  => '',
					'sanitize' => 'sanitize_title',
					'type'     => 'array',
				),
				'_color'                   => array(
					'default'  => '',
					'sanitize' => 'sanitize_hex_color',
				),
				'_color_text'              => array(
					'default'  => '',
					'sanitize' => 'sanitize_hex_color',
				),
				'_icon'                    => array(
					'default'  => '',
					'sanitize' => 'sanitize_text_field',
				),
				'_position'                => array(
					'default'  => 800,
					'sanitize' => 'absint',
				),
				'_tab_slug'                => array(
					'default'  => '',
					'sanitize' => 'sanitize_title',
				),
			);
		}


		/**
		 * Save settings in metaboxes.
		 *
		 * @since   1.0.0
		 * @version 1.1.4 Use settings map.
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

			foreach ( $this->get_settings_map() as $key => $set ) {
				$value = null;
				if ( ! array_key_exists( $key, $input ) && array_key_exists( 'default', $set ) ) {
					$value = $set['default'];
				} else {
					if ( array_key_exists( 'type', $set ) && 'array' === $set['type'] ) {
						$value = map_deep( $input[ $key ], $set['sanitize'] );
					} else {
						$value = call_user_func( $set['sanitize'], $input[ $key ] );
					}
				}
				if ( isset( $value ) ) {
					update_post_meta( $post_id, $key, $value );
				}
			}
		}
	}
}
