<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class UM_Account_Tabs
 */
class UM_Account_Tabs {


	/**
	 * @var UM_Account_Tabs
	 */
	private static $instance;


	/**
	 * @return UM_Account_Tabs
	 */
	static public function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * UM_Account_Tabs constructor.
	 */
	function __construct() {
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
	function get_this() {
		return $this;
	}


	/**
	 * @return um_ext\um_account_tabs\core\Account()
	 */
	function account() {
		if ( empty( UM()->classes['um_account_tabs_account'] ) ) {
			UM()->classes['um_account_tabs_account'] = new um_ext\um_account_tabs\core\Account();
		}
		return UM()->classes['um_account_tabs_account'];
	}


	/**
	 * @return um_ext\um_account_tabs\admin\Admin()
	 */
	function admin() {
		if ( empty( UM()->classes['um_account_tabs_admin'] ) ) {
			UM()->classes['um_account_tabs_admin'] = new um_ext\um_account_tabs\admin\Admin();
		}
		return UM()->classes['um_account_tabs_admin'];
	}


	/**
	 * @return um_ext\um_account_tabs\core\Common()
	 */
	function common() {
		if ( empty( UM()->classes['um_account_tabs_common'] ) ) {
			UM()->classes['um_account_tabs_common'] = new um_ext\um_account_tabs\core\Common();
		}
		return UM()->classes['um_account_tabs_common'];
	}
}


add_action( 'plugins_loaded', 'um_init_um_account_tabs', -10, 1 );
function um_init_um_account_tabs() {
	if ( function_exists( 'UM' ) ) {
		UM()->set_class( 'Account_Tabs', true );
	}
}