jQuery( function() {
	/**
	 * Apply responsive styles to the tab content.
	 * @see wp-content/plugins/ultimate-member/assets/js/um-account.js
	 * @see wp-content/plugins/ultimate-member/assets/js/um-functions.js
	 */
	wp.hooks.addAction( 'um_account_active_tab_inited', 'um-account-tabs', um_responsive );
	wp.hooks.addAction( 'um_after_account_tab_changed', 'um-account-tabs', um_responsive );

	/**
	 * Apply conditional fields script to the tab content.
	 * @see wp-content/plugins/ultimate-member/assets/js/um-conditional.js
	 */
	wp.hooks.addAction( 'um_account_active_tab_inited', 'um-account-tabs', apply_conditions );
	wp.hooks.addAction( 'um_after_account_tab_changed', 'um-account-tabs', apply_conditions );

	function apply_conditions() {
		jQuery( 'div.um-field.um-is-conditional' ).each( function( i, elem ) {
			for ( var ai in elem.attributes ) {
				var attribute = elem.attributes[ ai ];
				if ( /data-cond-\d+-field/.test( attribute.name ) ) {
					var $cond_field = jQuery( 'div.um-profile [name^="' + attribute.value + '"]' );
					if ( $cond_field.length ) {
						um_apply_conditions( $cond_field, false );
					}
				}
			};
		} );
	}
} );