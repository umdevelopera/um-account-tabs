<?php
/**
 * Customize the "Account tabs" table.
 *
 * @package um_ext\um_account_tabs\admin
 */

namespace um_ext\um_account_tabs\admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'um_ext\um_account_tabs\admin\Load_Edit' ) ) {


	/**
	 * Class Load_Edit.
	 *
	 * @package um_ext\um_account_tabs\admin
	 */
	class Load_Edit {


		/**
		 * Load_Edit constructor.
		 */
		public function __construct() {
			add_filter( 'manage_edit-um_account_tabs_columns', array( $this, 'columns_th' ) );
			add_action( 'manage_um_account_tabs_posts_custom_column', array( $this, 'columns_td' ), 10, 2 );
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
						echo get_the_title( $form_id ) . ' (' . $form_id . ')';
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
	}
}
