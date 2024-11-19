jQuery( function() {
	/**
	 * Apply responsive styles to the tab content.
	 * @see wp-content/plugins/ultimate-member/assets/js/um-account.js
	 * @see wp-content/plugins/ultimate-member/assets/js/um-functions.js
	 */
	wp.hooks.addAction( 'um_account_active_tab_inited', 'um-account-tabs', um_responsive );
	wp.hooks.addAction( 'um_after_account_tab_changed', 'um-account-tabs', um_responsive );
	wp.hooks.addAction( 'um_account_active_tab_inited', 'um-account-tabs', um_init_field_conditions );
	wp.hooks.addAction( 'um_after_account_tab_changed', 'um-account-tabs', um_init_field_conditions );
} );