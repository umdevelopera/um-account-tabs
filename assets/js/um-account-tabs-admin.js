jQuery( document.body ).on( 'click', '#UM_fonticons a.um-admin-modal-back:not(.um-admin-modal-cancel)', function ( e ) {
	var $input = jQuery( '#um_account_tab__icon' );
	var icon   = jQuery( e.currentTarget ).data('code') || $input.siblings( '.um-admin-icon-value' ).find( 'i' ).attr( 'class' );
	$input.val( icon );
} );