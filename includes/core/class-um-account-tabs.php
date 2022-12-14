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
		add_filter( 'um_call_object_Account_Tabs', array( &$this, 'get_this' ) );

		$this->common();
		if ( UM()->is_request( 'admin' ) ) {
			$this->admin();
		}
		if ( UM()->is_request( 'frontend' ) ) {
			$this->account();
		}
	}


	/**
	 * @return $this UM_Account_Tabs
	 */
	public function get_this() {
		return $this;
	}


	/**
	 * @return um_ext\um_account_tabs\core\Account()
	 */
	public function account() {
		if ( empty( UM()->classes['um_account_tabs_account'] ) ) {
			UM()->classes['um_account_tabs_account'] = new um_ext\um_account_tabs\core\Account();
		}
		return UM()->classes['um_account_tabs_account'];
	}


	/**
	 * @return um_ext\um_account_tabs\admin\Admin()
	 */
	public function admin() {
		if ( empty( UM()->classes['um_account_tabs_admin'] ) ) {
			UM()->classes['um_account_tabs_admin'] = new um_ext\um_account_tabs\admin\Admin();
		}
		return UM()->classes['um_account_tabs_admin'];
	}


	/**
	 * @return um_ext\um_account_tabs\core\Common()
	 */
	public function common() {
		if ( empty( UM()->classes['um_account_tabs_common'] ) ) {
			UM()->classes['um_account_tabs_common'] = new um_ext\um_account_tabs\core\Common();
		}
		return UM()->classes['um_account_tabs_common'];
	}
}

/**
 * Adds the class to the UM core.
 */
function um_init_um_account_tabs() {
	if ( function_exists( 'UM' ) ) {
		UM()->set_class( 'Account_Tabs', true );
	}
}
add_action( 'plugins_loaded', 'um_init_um_account_tabs', -10, 1 );
