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

			// Columns.
			add_filter( 'manage_edit-um_account_tabs_columns', array( $this, 'columns_th' ) );
			add_action( 'manage_um_account_tabs_posts_custom_column', array( $this, 'columns_td' ), 10, 2 );

			// Metaboxes.
			add_action( 'load-post.php', array( &$this, 'init_metaboxes' ), 9 );
			add_action( 'load-post-new.php', array( &$this, 'init_metaboxes' ), 9 );
			add_filter( 'wp_insert_post_data', array( &$this, 'filter_post_data' ), 10, 4 );

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
		 * Enqueue admin scripts.
		 *
		 * @global object $current_screen
		 */
		public function enqueue() {
			global $current_screen;
			if ( isset( $current_screen ) && 'um_account_tabs' === $current_screen->id ) {
				wp_enqueue_script(
					'um-account-tabs-admin',
					um_account_tabs_url . '/assets/js/um-account-tabs-admin.js',
					array( 'jquery' ),
					um_account_tabs_version,
					true
				);
			}
		}


		/**
		 * Adds custom columns to the table "Account tabs".
		 *
		 * @param array $columns  An array of columns.
		 *
		 * @return array
		 */
		public function columns_th( $columns ) {
			if ( isset( $columns['date'] ) ) {
				unset( $columns['date'] );
			}

			$columns['position'] = __( 'Position', 'um-account-tabs' );
			$columns['form']     = __( 'Embed form', 'um-account-tabs' );
			$columns['roles']    = __( 'Roles restriction', 'um-account-tabs' );
			$columns['date']     = __( 'Date', 'um-account-tabs' );

			return $columns;
		}


		/**
		 * Displays custom columns in the table "Account tabs"
		 *
		 * @param  string $column_name  A column slug.
		 * @param  int    $id           Post ID.
		 */
		public function columns_td( $column_name, $id ) {
			switch ( $column_name ) {

				case 'position':
					$position = get_post_meta( $id, '_position', true );
					if ( $position ) {
						echo absint( $position );
					}
					break;

				case 'form':
					$form_id = get_post_meta( $id, '_um_form', true );
					if ( $form_id ) {
						echo get_the_title( $id ) . ' (' . $id . ')';
					}
					break;

				case 'roles':
					$roles = get_post_meta( $id, '_can_have_this_tab_roles', true );
					if ( $roles ) {
						$all_roles = wp_roles()->role_names;
						$roles     = array_intersect_key( $all_roles, array_flip( $roles ) );
						echo implode( ', ', $roles );
					}
					break;
			}
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
		 * Add metaboxes to Add/Edit Account Tab screen.
		 */
		public function init_metaboxes() {
			global $current_screen;
			if ( isset( $current_screen ) && 'um_account_tabs' === $current_screen->id ) {
				add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue' ) );
				add_action( 'add_meta_boxes', array( &$this, 'add_metaboxes' ), 1 );
				add_action( 'save_post_um_account_tabs', array( &$this, 'save_metaboxes_data' ), 10, 3 );
			}
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
				'um-admin-custom-account-tab/um-form{' . um_account_tabs_path . '}',
				__( 'Pre-defined content', 'um-account-tabs' ),
				array( UM()->metabox(), 'load_metabox_custom' ),
				'um_account_tabs',
				'normal',
				'default'
			);

			add_meta_box(
				'um-admin-custom-account-tab/access{' . um_account_tabs_path . '}',
				__( 'Display Settings', 'um-account-tabs' ),
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
			if ( empty( $_POST ) ) {
				return;
			}

			check_admin_referer( 'update-post_' . $post_id );

			if ( UM()->external_integrations()->is_wpml_active() ) {
				global $sitepress;
				$tab_id = $sitepress->get_object_id( $post_id, 'um_account_tabs', true, $sitepress->get_default_language() );
				if ( $tab_id && $tab_id !== $post_id ) {
					return;
				}
			}

			if ( empty( $_POST['um_account_tab'] ) ) {
				return;
			}
			$input = map_deep( wp_unslash( $_POST['um_account_tab'] ), 'sanitize_text_field' );

			$form = '';
			if ( isset( $input['_um_form'] ) ) {
				$form = absint( $input['_um_form'] );
			}
			update_post_meta( $post_id, '_um_form', $form );

			$roles = array();
			if ( isset( $input['_can_have_this_tab_roles'] ) && is_array( $input['_can_have_this_tab_roles'] ) ) {
				$roles = $input['_can_have_this_tab_roles'];
			}
			update_post_meta( $post_id, '_can_have_this_tab_roles', $roles );

			$color = isset( $input['_color'] ) ? sanitize_hex_color( $input['_color'] ) : '';
			update_post_meta( $post_id, '_color', $color );

			$icon = isset( $input['_icon'] ) ? sanitize_key( $input['_icon'] ) : '';
			update_post_meta( $post_id, '_icon', $icon );

			$position = isset( $input['_position'] ) ? absint( $input['_position'] ) : '';
			update_post_meta( $post_id, '_position', $position );
		}
	}
}
