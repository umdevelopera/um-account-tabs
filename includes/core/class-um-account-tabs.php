<?php
/**
 * Inits the extension.
 *
 * @package um_ext\um_account_tabs\core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class UM_Account_Tabs
 */
class UM_Account_Tabs {


	/**
	 * An instance of the class.
	 *
	 * @var UM_Account_Tabs
	 */
	private static $instance;


	/**
	 * Creates an instance of the class.
	 *
	 * @return UM_Account_Tabs
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * UM_Account_Tabs constructor.
	 */
	public function __construct() {
		$this->common();
		if( UM()->is_ajax() ) {

		} elseif ( UM()->is_request( 'admin' ) ) {
			$this->admin();
		} elseif ( UM()->is_request( 'frontend' ) ) {
			$this->account();
		}
	}


	/**
	 * @return um_ext\um_account_tabs\core\Account()
	 */
	public function account() {
		if ( empty( UM()->classes['um_account_tabs_account'] ) ) {
			require_once um_account_tabs_path . 'includes/core/class-account.php';
			UM()->classes['um_account_tabs_account'] = new um_ext\um_account_tabs\core\Account();
		}
		return UM()->classes['um_account_tabs_account'];
	}


	/**
	 * @return um_ext\um_account_tabs\admin\Admin()
	 */
	public function admin() {
		if ( empty( UM()->classes['um_account_tabs_admin'] ) ) {
			require_once um_account_tabs_path . 'includes/admin/class-admin.php';
			UM()->classes['um_account_tabs_admin'] = new um_ext\um_account_tabs\admin\Admin();
		}
		return UM()->classes['um_account_tabs_admin'];
	}


	/**
	 * @return um_ext\um_account_tabs\core\Common()
	 */
	public function common() {
		if ( empty( UM()->classes['um_account_tabs_common'] ) ) {
			require_once um_account_tabs_path . 'includes/core/class-common.php';
			UM()->classes['um_account_tabs_common'] = new um_ext\um_account_tabs\core\Common();
		}
		return UM()->classes['um_account_tabs_common'];
	}
}
