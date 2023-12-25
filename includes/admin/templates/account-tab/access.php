<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$tab_id = $post->ID;
if ( UM()->external_integrations()->is_wpml_active() ) {
	global $sitepress;
	$default_lang_tab_id = $sitepress->get_object_id( $tab_id, 'um_account_tabs', true, $sitepress->get_default_language() );
	if ( $default_lang_tab_id && $default_lang_tab_id !== $tab_id ) {
		$tab_id = $default_lang_tab_id;
		echo '<p>' . esc_html__( 'These settings are obtained from a Account tab in the default language', 'um-account-tabs' ) . '</p>';
	}
}

$all_roles = UM()->roles()->get_roles();

$fields = array(
	array(
		'id'      => '_can_have_this_tab_roles',
		'type'    => 'select',
		'multi'   => true,
		'label'   => __( 'Show on these roles accounts', 'um-account-tabs' ),
		'tooltip' => __( 'You can choose the roles in whose accounts this tab should be displayed. The tab is displayed in all accounts if the setting is empty.', 'um-account-tabs' ),
		'options' => $all_roles,
		'value'   => get_post_meta( $tab_id, '_can_have_this_tab_roles', true ),
	),
);

UM()->admin_forms(
	array(
		'class'     => 'um-account-tab-access um-top-label',
		'prefix_id' => 'um_account_tab',
		'fields'    => $fields,
	)
)->render_form();
